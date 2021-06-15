<?php

namespace App\Controller\Site;

use App\Controller\EncryptController;
use App\Entity\CartProductDTO;
use App\Entity\CartProductListDTO;
use App\Entity\City;
use App\Entity\Countries;
use App\Entity\CustomUser;
use App\Entity\Format;
use App\Entity\Material;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Regions;
use App\Entity\Zone;
use App\Services\GlobalManager;
use App\Services\ProductManager;
use App\Services\SessionsHandler;
use Doctrine\ORM\EntityManagerInterface;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart")
 */
class CartController extends AbstractController

{

    private $sessionHandler;
    private $em;
    private $productManager;
    private $encrypt;

    private $globalManager;

    function __construct(SessionsHandler $sessionHandler,
                         ProductManager $productManager, EntityManagerInterface $em,
                         Encryptor $encryptor, GlobalManager $globalManager)
    {
        $this->sessionHandler = $sessionHandler;
        $this->em = $em;
        $this->productManager = $productManager;
        $this->encrypt = $encryptor;
        $this->globalManager = $globalManager;
    }

    /*
       @Route ("/test", name="test_finish_order", methods={"GET"})

     public function testFinishOrderView()
       {
           return  $this->redirectToRoute('checkout-success', ['hashedText' => $this->encrypt->encrypt(37)]);
       }*/

    /**
     * @Route ("/", name="get_cart", methods={"GET"})
     */
    public function index(): Response
    {

        $products = [];
        $productsAdded = $this->sessionHandler->getItemsInCart();
        $zones = $this->em->getRepository(Zone::class)->findAll();
        $materials = $this->em->getRepository(Material::class)->findAll();
        $formats = $this->em->getRepository(Format::class)->findAll();
        $accordionItems = $this->ProductsAccordion();
        if ($productsAdded) {
            foreach ($this->sessionHandler->getItemsInCart() as $key => $value) {
                $currentProduct = $this->em->getRepository(Product::class)->find($key);
                $products[] = $this->productManager->AssignDataToProductCartListDTO($currentProduct, $value);
            }
        }

        return $this->render('site/cart/index.html.twig', [
            'title' => 'Finalizar pedido',
            'products' => $products,
            'zones' => $zones,
            'materials' => $materials,
            'formats' => $formats,
            'accordion_items' => $accordionItems,
        ]);
    }

    /**
     * @Route ("/checkout-post", name="checkout", methods={"POST"})
     */
    public function CheckOut(Request $request): Response
    {
        if (strlen($this->sessionHandler->getSessionId()) < 1) {
            return $this->redirectToRoute('home');
        }
        $products = $this->sessionHandler->RefreshCartItemsSession($request);
        if ($products) {

            if ($oldOrder = $this->em->getRepository(Order::class)
                ->findOneBy(['session' => $this->sessionHandler->getSessionId()])) {
                $this->UpdateOrder($products, $oldOrder);
                $this->addFlash('success', 'Rellena tus datos para finalizar tu pedido!');
                return $this->redirectToRoute('checkout-get',
                    [
                        'hashedText' => $this->encrypt->encrypt($oldOrder->getId()),
                        'sessionHashed' => $this->encrypt->encrypt($this->sessionHandler->getSessionId()),
                    ]);
            } else {
                $this->addFlash('success', 'Orden creada, rellena tus datos para finalizar tu pedido!');
                return $this->redirectToRoute('checkout-get',
                    [
                        'hashedText' => $this->encrypt->encrypt($this->CreateOrder($products)->getId()),
                        'sessionHashed' => $this->encrypt->encrypt($this->sessionHandler->getSessionId()),
                    ]);

            }

        } else {
            if (strlen($this->sessionHandler->getSessionId()) > 5) {
                if ($currentOrder = $this->em->getRepository(Order::class)->findOneBy(['session' => $this->sessionHandler->getSessionId()])) {
                    if (!$currentOrder->getIsActive()) {
                        $this->em->remove($currentOrder);
                    }
                }
            }
            $this->addFlash('fail', 'Ha habido un error al procesar tu pedido, intentalo de nuevo.');
            return $this->redirectToRoute('get_cart');
        }

    }

    /**
     * @Route ("/checkout/{hashedText}/{sessionHashed}", name="checkout-get", methods={"GET"})
     */
    public function renderCheckoutView(string $hashedText, string $sessionHashed): Response
    {

        try {
            $order = $this->em->getRepository(Order::class)->find($this->encrypt->decrypt($hashedText));
            if (strcmp($order->getSession(), $this->encrypt->decrypt($sessionHashed)) != 0) {
                $this->redirectToRoute('home');
            }
        } catch (\Exception $e) {
            return $this->redirectToRoute('home');
        }
        $productsDTO = [];
        $orderDetails = $this->em->getRepository(OrderDetail::class)->findBy(['ClientOrder' => $order]);
        foreach ($orderDetails as $orderDetail) {
            $productsDTO[] = $this->productManager->AssignDataToProductCartListDTO($orderDetail->getProduct(), $orderDetail->getQuantity());

        }

        $regions = $this->em->getRepository(Regions::class)->findAll();
        return $this->render('site/cart/checkout.html.twig', [
            'title' => 'Datos facturacion',
            'products' => $productsDTO,
            'order' => $order->getId(),
            'regions' => $regions,

        ]);

    }

    /**
     * @Route ("/finish-order", name="finish_order", methods={"POST"})
     */
    public function FinishOrder(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $order = $this->em->getRepository(Order::class)->find($request->request->get('orderId'));
        if ($order) {
            //$customUser = $this->AssignDataToNewCustomUser($request);
            if ($existingEmail = $this->em->getRepository(CustomUser::class)->findOneBy(['email' => trim($request->request->get('email'))])){
               $customUser = $this->AssignDataToExistingCustomUser($existingEmail,$request);
            } else {
                $customUser = $this->AssignDataToNewCustomUser($request);
            }

            if ($this->globalManager->CheckErrorsOnValidation($this->globalManager->ValidatorCustomUser($customUser))) {
                return $this->redirectToRoute('get_cart');
            }
            $this->em->persist($customUser);
            $this->em->flush();
            $order->setClient($customUser->getId())
                ->setIsActive(true);
            $orderReference = $order->getId() . '-' . substr($customUser->getName(), 0, 2) .
                substr($customUser->getSurname(), 0, 2) . '-' . date_format($order->getCreatedAt(), "d/m/Y");
            $order->setReference($orderReference);
            $this->em->persist($order);
            $this->em->flush();

            $this->sessionHandler->invalidateSession();
            $this->addFlash('success', 'Pedido realizado, pronto contactaremos contigo');
            return  $this->redirectToRoute('checkout-success', ['hashedText' => $this->encrypt->encrypt($order->getId())]);
            //return $this->redirectToRoute('home');


        }
        $this->addFlash('fail', 'Ha ocurrido un error localizando tu orden, intentalo en unos minutos.');
        return $this->redirectToRoute('home');

    }

    /**
     * @Route ("/checkout-success/{hashedText}", name="checkout-success", methods={"GET"})
     */
    public function CheckOutSuccess(string $hashedText)
    {
        $order = $this->em->getRepository(Order::class)->find($this->encrypt->decrypt($hashedText));
        if ($order){
            return $this->render('site/cart/checkout_success.html.twig', [
                'title' => 'Pedido realizado',
                'order_number' => $order->getId(),
                'hashedText' => $this->encrypt->encrypt($order->getId()),
            ]);
        }

        $this->addFlash('fail', 'Ha ocurrido un error localizando tu orden, intentalo en unos minutos. Disculpa las molestias.');
        return $this->redirectToRoute('home');

    }
    /**
     * @Route("/cities/{id<\d+>}", name="public_get_cities_json", methods={"GET"})
     */
    public function getCities(int $id): Response
    {

        $region = $this->em->getRepository(Regions::class)->find($id);
        $cities = $this->em->getRepository(City::class)->findByRegionId($region->getId());

        return new JsonResponse($cities);
    }

    /**
     * @Route ("/add", name="add_cart_item", methods={"POST"})
     */
    public function addItemToCart(Request $request): JsonResponse
    {

        $numberItems = $this->sessionHandler->addItemToSession($request);
        if ($numberItems) {
            return new JsonResponse($numberItems, 201);
        } else {
            return new JsonResponse('', 404);
        }

    }

    /**
     * @Route ("/add-from-Cart", name="add_cart_item_from_cart", methods={"POST"})
     */
    public function addItemToCartFromCartView(Request $request): RedirectResponse
    {
        //dd($request->request->all());
      //  dd($this->sessionHandler->addItemToSession($request));
        if($this->sessionHandler->addItemToSessionFromCartView($request) != null){
            $this->addFlash('success', 'Articulo agregado a tu pedido');
        } else {
            $this->addFlash('fail', 'No se pudo agregar tu articulo al pedido, intentalo en unos minutos');
        }
        return $this->redirectToRoute('get_cart');
    }


    /**
     * @Route ("/remove-item/{id<\d+>}", name="remove_cart_item", methods={"GET"})
     */
    public function removeItemFromCart(int $id, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $numberItems = $this->sessionHandler->removeItemFromCartSession($id, $request);

        if ($numberItems == 400) {
            $this->addFlash('fail', 'No se han encontrado productos en la cesta de compra');
        } elseif ($numberItems == 404) {
            $this->addFlash('fail', 'Articulo no encontrado');
        } else {
            $this->addFlash('success', 'Articulo eliminado satisfactoriamente.');
        }
        return $this->redirectToRoute('get_cart');

    }

    public function UpdateOrder($productsInCartSession, Order $oldOrder)
    {
        foreach ($productsInCartSession as $key => $value) {

            $currentProduct = $this->em->getRepository(Product::class)->find($key);
            if ($orderDetail = $this->em->getRepository(OrderDetail::class)->findOneBy(['ClientOrder' => $oldOrder, 'Product' => $currentProduct])) {
                $orderDetail->setQuantity((int)$value);
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->setProduct($currentProduct);
                $orderDetail->setQuantity((int)$value);
                $orderDetail->setClientOrder($oldOrder);
                if ($currentProduct->getIdType()->getId() == $this->productManager->getOutletId()){
                    $orderDetail->setPrice($currentProduct->getPrice());
                } else {
                    $orderDetail->setPrice(0);
                }
            }
            $this->em->persist($orderDetail);

        }
        $this->em->flush();
    }

    public function CreateOrder($productsInCartSession): Order
    {
        $newOrder = new Order();
        $newOrder->setCreatedAt(new \DateTime('now'));
        $newOrder->setIsActive(false);
        $newOrder->setSession($this->sessionHandler->getSessionId());
        $this->em->persist($newOrder);

        foreach ($productsInCartSession as $key => $value) {
            $orderDetail = new OrderDetail();
            $currentProduct = $this->em->getRepository(Product::class)->find($key);
            $orderDetail->setProduct($currentProduct);
            $orderDetail->setQuantity((int)$value);
            $orderDetail->setClientOrder($newOrder);
            if ($currentProduct->getIdType()->getId() == $this->productManager->getOutletId()){
                $orderDetail->setPrice($currentProduct->getPrice());
            }else {
                $orderDetail->setPrice(0);
            }

            $this->em->persist($orderDetail);

        }
        $this->em->flush();
        return $newOrder;
    }

    public function AssignDataToNewCustomUser(Request $request): CustomUser
    {
        $newCustomUser = new CustomUser();
        return $this->AssignDataCustomUser($newCustomUser, $request);
        /*$newCustomUser
            ->setName(trim($request->request->get('name')))
            ->setSurname(trim($request->request->get('surname')))
            ->setCompanyName('ANONIMO')
            ->setNIF('ANONIMO')
            ->setCountry($this->em->getRepository(Countries::class)->find(1))
            ->setAddress('ANONIMO')
            ->setRegion($this->em->getRepository(Regions::class)->find(trim($request->request->get('region')))->getName())
            ->setCity($this->em->getRepository(City::class)->find(trim($request->request->get('city')))->getName())
            ->setPostalCode('ANONIMO')
            ->setPhone(trim($request->request->get('phone')))
            ->setEmail(trim($request->request->get('email')))
            ->setAdditionalInformation(trim($request->request->get('additionalInfo')));*/

        //return $newCustomUser;
    }

    public function AssignDataToExistingCustomUser (CustomUser $customUser,Request $request): CustomUser
    {
        return $this->AssignDataCustomUser($customUser, $request);
    }

    public function AssignDataCustomUser(CustomUser $customUser,Request $request): CustomUser
    {
        //$newCustomUser = new CustomUser();
        $customUser
            ->setName(trim($request->request->get('name')))
            ->setSurname(trim($request->request->get('surname')))
            ->setCompanyName('ANONIMO')
            ->setNIF('ANONIMO')
            ->setCountry($this->em->getRepository(Countries::class)->find(1))
            ->setAddress('ANONIMO')
            ->setRegion($this->em->getRepository(Regions::class)->find(trim($request->request->get('region')))->getName())
            ->setCity($this->em->getRepository(City::class)->find(trim($request->request->get('city')))->getName())
            ->setPostalCode('ANONIMO')
            ->setPhone(trim($request->request->get('phone')))
            ->setEmail(trim($request->request->get('email')))
            ->setAdditionalInformation(trim($request->request->get('additionalInfo')));

        return $customUser;
    }

    public function ProductsAccordion(): array
    {

        $productsAccordion = [];
        $formats = $this->em->getRepository(Format::class)->findAll();
        $zones = $this->em->getRepository(Zone::class)->findAll();
        //dd($zones);
        $productsByFormatAndZone = [];
        foreach ($formats as $format) {
            foreach ($zones as $zone) {
                $products = $this->em->
                getRepository(Product::class)->GetProductsByFormatAndZone($format->getId(),$zone->getId());
                if (!$products){
                    continue;
                }
                $cartProductDTO = $this->AssignDataToCartProductTo($products);
                $productsByFormatAndZone[] = [ $zone->getName() =>
                    $cartProductDTO];

            }
//            dd($productsByFormatAndZone);
        $productsAccordion[] = [$format->getName() => $productsByFormatAndZone];
        $productsByFormatAndZone = [];
        }
       // dd($productsAccordion);
        return $productsAccordion;


    }


    public function AssignDataToCartProductTo($products): array
    {
        $arrayProductsCartDTO = [];
        foreach ($products as $product) {
            $cartProductDto = new CartProductDTO();
            $cartProductDto
                ->setId($product->getId())
                ->setName($product->getName())
                ->setReference($product->getReference())
                ->setBrand($product->getBrand()->getName())
                ->setCountry($product->getCountry()->getCountryName())
                ->setImageCoverUrl($product->getImageCover())
                ->setMaterial($product->getIdMaterial()->getName());
            $arrayProductsCartDTO[] = $cartProductDto;
        }

        return $arrayProductsCartDTO;
    }


}

