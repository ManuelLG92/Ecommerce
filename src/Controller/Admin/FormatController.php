<?php


namespace App\Controller\Admin;


use App\Entity\Decorated;
use App\Entity\Format;
use App\Entity\ProductType;
use App\Entity\Zone;
use App\Services\ZoneManager;
use App\Services\GlobalManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FormatController
 * @package App\Controller
 * @Route ("/admin/format")
 */
class FormatController extends AbstractController
{
    private $em;
    private $globalManager;
    private $validator;
    private $message;

    public static $successOperation = "success_message";
    public static  $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager, GlobalManager $globalManager
            , ValidatorInterface $validator){

        $this->em = $entityManager;
        $this->globalManager = $globalManager;
        $this->validator = $validator;
    }

    /**
     * @Route ("/",name="get_all_formats", methods={"GET"})
     */
    public function GetAllFormats(): Response
    {
        $formats = $this->em->getRepository(Format::class)->findAll();
        return $this->render('admin/format/index.html.twig', [
            'title' => 'Formatos',
            'formats' => $formats,
        ]);
    }

    /**
     * @Route("/create", name="create_format_get", methods={"GET"})
     */
    public function CreateFormatGet(): Response
    {
        $format= new Format();
        return $this->render('admin/format/new.html.twig', [
            'title' => 'Crear Formato',
            'format' => $format,
        ]);
    }

    /**
     * @Route ("/create-post", name="create_format_post", methods={"POST"})
     */
    public function CreateFormatPost(Request $request): Response
    {
        $format = new Format();
        $format->setName(trim($request->request->get('format')));

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($format))){
            return $this->redirectToRoute('get_all_formats');
        }

        try {
            $this->em->persist($format);
            $this->em->flush();
        } catch (\Exception $exception){
            $this->addFlash('fail','Ha habido un error al guardar el formato '. $format->getName() .' en al base de datos ');
        }
        $this->addFlash('success','Formato ' . $format->getName() . ' creado satisfactoriamente.');
        return $this->redirectToRoute('get_all_formats');

    }

    /**
     * @Route ("/edit/{id<\d+>}",name="edit_format_get", methods={"GET"})
     */
    public function EditFormatGet(int $id): Response
    {
        $format = $this->em->getRepository(Format::class)->find($id);

        if (!$format){
            $this->message = "Formato  #" . $id . " no encontrado.";
            $this->addFlash($this->getParameter(self::$failOperation),$this->message);
            return $this->redirectToRoute("get_all_formats");
        }
        return $this->render('admin/format/edit.html.twig', [
            'title' => 'Editar Formato',
            'format' => $format,
        ]);
    }


    /**
     * @Route ("/edit",name="edit_format_put", methods={"PUT"})
     */
    public function EditFormatPut(Request $request) :Response
    {

        $id = intval($request->request->get('idFormat'));

        $format = $this->em->getRepository(Format::class)->find($id);

        if (!$format){
            $this->addFlash($this->getParameter(self::$failOperation),"Formato #" . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_formats');
        }

        $format->setName(trim($request->request->get('format')));
        //$errorsValidation = $this->categoryManager->ValidateZoneFields($format);

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($format))){
            return  $this->redirectToRoute('get_all_formats');
        }

        try {
            $this->em->persist($format);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation),"Formato ". $format->getName() ." editado satisfactoriamente.");
            return  $this->redirectToRoute('get_all_formats');
        } catch (\Exception $exception){
            $this->addFlash($this->getParameter(self::$failOperation),"El Formato ". $format->getName() ." no se ha podido editar.");
            return  $this->redirectToRoute('get_all_formats');
        }

    }




    /*@Route ("/create",name="create_product_type_post", methods={"POST"})

    public function CreateTypePost(Request $request)
    {

        $type = new ProductType();
        $type->setName(trim($request->request->get('name')));

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($type))){
            return  $this->redirectToRoute('create_zone_get');
        }
        try {
            $this->em->persist($type);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation),"Zona creada satisfactoriamente.");
            return  $this->redirectToRoute('get_all_zones');
        } catch (\Exception $exception){
            $this->addFlash($this->getParameter(self::$failOperation),"La Zona no se ha podido crear.");
            return  $this->redirectToRoute('create_zone_get');
        }

    }*/


  /* Route ("/delete/{id<\d+>}", name="delete_product_type")

    public function DeleteProductType(Request $request, int $id)
    {
        $productType = $this->em->getRepository(Zone::class)->find($id);

        if (!$productType){
            $this->addFlash($this->getParameter(self::$failOperation),"Tipo de producto #" . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_zones');
        }

        if (!$this->CheckCsrfToken('delete'.$productType->getId(), $request)){
            $this->addFlash($this->getParameter(self::$failOperation),"Token CSRF no valido.");
            return $this->redirectToRoute('get_all_zones');
        }

        try {
            $this->em->remove($productType);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation),"Producto " . $productType->getName() . ' eliminado.');
            return $this->redirectToRoute('get_all_zones');
        } catch (\Exception $exception){
            $this->addFlash($this->getParameter(self::$failOperation),"El producto " . $productType->getName() . ' No se pudo eliminar.');
            return $this->redirectToRoute('get_all_zones');
        }

    }

    public function CheckCsrfToken(string $idCsrf, Request $request)
    {

        $this->isCsrfTokenValid($idCsrf, $request->request->get('_token')) ? $validCsrf = true : $validCsrf = false;

        return $validCsrf;
    }*/



}