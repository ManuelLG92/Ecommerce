<?php

namespace App\Controller\Admin;

use App\Entity\CustomUser;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\OrderDetailDTO;
use App\Entity\OrderDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route ("/admin/order")
 */
class OrderController extends AbstractController
{
    private $em;
    private $encryptor;
    function __construct(EntityManagerInterface $em, Encryptor $encryptor)
    {
        $this->em = $em;
        $this->encryptor = $encryptor;
    }


    /**
     * @Route ("/", name="get_all_orders", methods={"GET"})
     */
    public function GetAllOrders(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        $ordersDTO = $this->GetAllOrdersInDTO($orders);

        return $this->render('admin/order/all.html.twig', [
            'title' => 'Pedidos',
            'orders' => $ordersDTO,
        ]);
    }

    /**
     * @Route ("/show/{id<\d+>}", name="show_order", methods={"GET"})
     */
    public function ShowOrder(Order $order): Response
    {

        $orderDTO = $this->ConvertOrderToOrderDTO($order);
        $orderDetailsDTO = $this->getOrderDetailsDTO($this->em->getRepository(OrderDetail::class)
            ->findBy(['ClientOrder' => $order->getId()]));
        $hashed = $this->encryptor->encrypt($orderDTO->getId());
        $totalQuantity = $this->getQuantityByOrder($order);
        return $this->render('admin/order/show_order.html.twig', [
            'title' => 'Pedido',
            'order' => $orderDTO,
            'orderDetails' => $orderDetailsDTO,
            'total_quantity' => $totalQuantity,
            'hashedText' => $hashed,
        ]);
    }



    public function ConvertOrderToOrderDTO(Order $order): OrderDTO
    {

        $orderDTO = new OrderDTO();
        $user = $this->em->getRepository(CustomUser::class)->find($order->getClient());

        $quantity = $this->getQuantityByOrder($order);
        $orderDTO->setUser($user)
                ->setIsActive($order->getIsActive())
                ->setCreatedAt($order->getCreatedAt())
                ->setReference($order->getReference())
                ->setQuantity($quantity)
                ->setId($order->getId());
        return $orderDTO;
    }

    public function GetAllOrdersInDTO($orders): array
    {
        $ordersDTO = [];
        foreach ($orders as $order) {
            if ($order->getIsActive()){
                $orderDTO = $this->ConvertOrderToOrderDTO($order);
                $ordersDTO[] = $orderDTO;
            }
        }
        return $ordersDTO;
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function getQuantityByOrder(Order $order)
    {
        $quantity = 0;
        $orderDetails = $this->em->getRepository(OrderDetail::class)->findBy(['ClientOrder' => $order->getId()]);
        foreach ($orderDetails as $orderDetail) {
            $quantity += $orderDetail->getQuantity();
        }
        return $quantity;
    }

    public function getOrderDetailsDTO(array $orderDetails): array
    {
        $orderDetailsDTO = [];

        foreach ($orderDetails as $orderDetail) {
            $orderDetailDTO = new OrderDetailDTO();
            $orderDetailDTO->setId($orderDetail->getId())
                ->setQuantity($orderDetail->getQuantity())
                ->setProduct($orderDetail->getProduct());
            $orderDetailsDTO[] = $orderDetailDTO;
        }
        return $orderDetailsDTO;

    }


}
