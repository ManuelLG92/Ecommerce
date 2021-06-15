<?php


namespace App\Controller\Admin;


use App\Entity\Decorated;
use App\Entity\Material;
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
 * Class MaterialController
 * @package App\Controller
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route ("/admin/material")
 */
class MaterialController extends AbstractController
{
    private $em;
    private $globalManager;
    private $validator;
    private $message;

    public static $successOperation = "success_message";
    public static  $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager,GlobalManager $globalManager
            , ValidatorInterface $validator){

        $this->em = $entityManager;
        $this->globalManager = $globalManager;
        $this->validator = $validator;
    }

    /**
     * @Route ("/", name="get_all_materials", methods={"GET"})
     */
    public function GetAllMaterials()
    {
        $Materials = $this->em->getRepository(Material::class)->findAll();
        return $this->render('admin/material/index.html.twig', [
            'title' => 'Materiales',
            'Materials' => $Materials,
        ]);
    }

    /**
     * @Route ("/edit/{id<\d+>}",name="edit_material_get", methods={"GET"})
     */
    public function EditMaterialGet(int $id): Response
    {
        $material = $this->em->getRepository(Material::class)->find($id);

        if (!$material){
            $this->message = "Material  #" . $id . " no encontrado.";
            $this->addFlash($this->getParameter(self::$failOperation),$this->message);
            return $this->redirectToRoute("get_all_materials");
        }
        return $this->render('admin/material/edit.html.twig', [
            'title' => 'Editar Material',
            'material' => $material,
        ]);
    }



    /**
     * @Route ("/edit",name="edit_material_put", methods={"PUT"})
     */
    public function EditMaterialPut(Request $request) :Response
    {

        $id = intval($request->request->get('idMaterial'));

        $material = $this->em->getRepository(Material::class)->find($id);

        if (!$material){
            $this->addFlash($this->getParameter(self::$failOperation),"Material #" . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_materials');
        }

        $material->setName(trim($request->request->get('name')));
        //$errorsValidation = $this->categoryManager->ValidateZoneFields($material);

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($material))){
            return  $this->redirectToRoute('get_all_materials');
        }

        try {
            $this->em->persist($material);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation),"Material ". $material->getName()
                ." editado satisfactoriamente.");
            return  $this->redirectToRoute('get_all_materials');
        } catch (\Exception $exception){
            $this->addFlash($this->getParameter(self::$failOperation),"El material ". $material->getName()
                ." no se ha podido editar.");
            return  $this->redirectToRoute('get_all_materials');
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