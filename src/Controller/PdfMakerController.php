<?php

namespace App\Controller;

use App\Entity\CustomUser;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Services\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfMakerController extends AbstractController
{
    private $encrypt;
    private $em;
    private $productManager;
    function  __construct( Encryptor $encryptor, EntityManagerInterface $em, ProductManager $productManager)
    {
        $this->em = $em;
        $this->encrypt = $encryptor;
        $this->productManager = $productManager;
    }

    /**
     * @Route("/pdf-test", name="pdf_test")
     */
    public function pdfTest(Pdf $pdf)
    {
        $order = $this->em->getRepository(Order::class)->find(25);
        $orderDetails = $this->em->getRepository(OrderDetail::class)->findBy(['ClientOrder' => 25]);
        $client = $this->em->getRepository(CustomUser::class)->find($order->getClient());

        return $this->render('pdf_maker/pdf_template.html.twig',
            [
                'title' => 'titulo',
                'order' => $order,
                'order_details' => $orderDetails,
                'client' => $client,
            ]);

    }

    /**
     * @Route("/pdf-maker/{hashedText}", name="pdf_maker")
     */
    public function pdfMaker($hashedText ,Pdf $pdf): Response
    {
        $order = $this->em->getRepository(Order::class)->find($this->encrypt->decrypt($hashedText));
        $orderDetails = $this->em->getRepository(OrderDetail::class)->findBy(['ClientOrder' => $order->getId()]);
        $client = $this->em->getRepository(CustomUser::class)->find($order->getClient());
        $outletId = $this->productManager->getOutletId();

        $html = $this->renderView('pdf_maker/pdf_template.html.twig',
            [
                'title' => 'titulo',
                'order' => $order,
                'order_details' => $orderDetails,
                'client' => $client,
                'outlet_id' => $outletId,
            ]);
        $fileName = 'reference-'.rand().'.pdf';

        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            [
                'lowquality' => false,
                'encoding' => 'utf-8',
                'print-media-type' => true,
                'images' => true,
                'enable-javascript' => true,
                'page-size' => 'A4',
                'Content-Type' => 'application/pdf',
                'Content-disposition' => 'attachement; filename="' . $fileName . '"'
            ]
        );
   /*     return new PdfResponse(
            $pdf->getOutputFromHtml($html ),
            $fileName
        );*/
    }
}
