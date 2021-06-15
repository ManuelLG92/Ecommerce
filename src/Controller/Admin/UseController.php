<?php


namespace App\Controller\Admin;


use App\Entity\Decorated;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\ProductUse;
use App\Entity\Zone;
use App\Services\ZoneManager;
use App\Services\GlobalManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UseController
 * @package App\Controller
 * @Route ("/admin/use")
 */
class UseController extends AbstractController
{
    private EntityManagerInterface $em;
    private GlobalManager $globalManager;
    private ValidatorInterface $validator;

    public static string $successOperation = "success_message";
    public static string $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager, GlobalManager $globalManager
            , ValidatorInterface $validator){

        $this->em = $entityManager;
        $this->globalManager = $globalManager;
        $this->validator = $validator;
    }

    /**
     * @Route ("/", name="get_all_uses", methods={"GET"})
     */
    public function GetAllUses(): Response
    {
        $uses = $this->em->getRepository(ProductUse::class)->findAll();
        return $this->render('admin/uses/index.html.twig', [
            'title' => 'Uso del producto',
            'uses' => $uses,
        ]);
    }

    /**
     * @Route ("/edit/{id<\d+>}",name="edit_use_get", methods={"GET"})
     */
    public function EditUseGet(int $id): Response
    {
        $use = $this->em->getRepository(ProductUse::class)->find($id);
        //dd($use);
        if (!$use){
            $message = "Uso  #" . $id . " no encontrado.";
            $this->addFlash($this->getParameter(self::$failOperation), $message);
            return $this->redirectToRoute("get_all_uses");
        }
        return $this->render('admin/uses/edit.html.twig', [
            'title' => 'Editar uso del producto',
            'use' => $use,
        ]);
    }



    /**
     * @Route ("/edit",name="edit_use_put", methods={"PUT"})
     */
    public function EditUsePut(Request $request) :Response
    {

        $id = intval($request->request->get('idUse'));

        $use = $this->em->getRepository(ProductUse::class)->find($id);

        if (!$use){
            $this->addFlash($this->getParameter(self::$failOperation),"Uso #" . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_uses');
        }

        $use->setName(trim($request->request->get('name')));
        //$errorsValidation = $this->categoryManager->ValidateZoneFields($use);

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($use))){
            return  $this->redirectToRoute('get_all_uses');
        }

        try {
            $this->em->persist($use);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation),"Uso ". $use->getName()
                . " editado satisfactoriamente.");
            return  $this->redirectToRoute('get_all_uses');
        } catch (\Exception $exception){
            $this->addFlash($this->getParameter(self::$failOperation),"El uso ". $use->getName()
                ." no se ha podido editar.");
            return  $this->redirectToRoute('get_all_uses');
        }

    }


 /* Route ("/create",name="create_product_type", methods={"GET"})

    public function CreateProductTypeGet()
    {
        $zone = new Zone();
        return $this->render('category/new_category.html.twig', [
            'title' => 'Crear Type producto',
            'category' => $zone,
        ]);
    }*/


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