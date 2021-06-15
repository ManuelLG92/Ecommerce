<?php


namespace App\Controller\Admin;


use App\Entity\Decorated;
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
 * Class DecoratedController
 * @package App\Controller
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route ("/admin/decorated")
 */
class DecoratedController extends AbstractController
{
    private $em;
    private $globalManager;
    private $validator;
    private $message;

    public static $successOperation = "success_message";
    public static $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager, GlobalManager $globalManager
        , ValidatorInterface $validator)
    {

        $this->em = $entityManager;
        $this->globalManager = $globalManager;
        $this->validator = $validator;
    }

    /**
     * @Route ("/",name="get_all_decorated", methods={"GET"})
     */
    public function GetAllDecorated()
    {
        $Decorated = $this->em->getRepository(Decorated::class)->findAll();
        return $this->render('admin/decorated/index.html.twig', [
            'title' => 'Tipos de decorado',
            'Decorated' => $Decorated,
        ]);
    }

    /**
     * @Route ("/edit/{id<\d+>}",name="edit_decorated_get", methods={"GET"})
     */
    public function EditDecoratedGet(int $id): Response
    {
        $decorated = $this->em->getRepository(Decorated::class)->find($id);

        if (!$decorated) {
            $this->message = "Decorado  #" . $id . " no encontrado.";
            $this->addFlash($this->getParameter(self::$failOperation), $this->message);
            return $this->redirectToRoute("get_all_decorated");
        }
        return $this->render('admin/decorated/edit.html.twig', [
            'title' => 'Editar Decorado',
            'decorated' => $decorated,
        ]);
    }


    /**
     * @Route ("/edit",name="edit_decorated_put", methods={"PUT"})
     */
    public function EditProductTypePut(Request $request): Response
    {

        $id = intval($request->request->get('idDecorated'));

        $decorated = $this->em->getRepository(Decorated::class)->find($id);

        if (!$decorated) {
            $this->addFlash($this->getParameter(self::$failOperation), "Decorado #" . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_decorated');
        }

        $decorated->setName(trim($request->request->get('name')));
        //$errorsValidation = $this->categoryManager->ValidateZoneFields($decorated);
        // dd($this->validator->validate($decorated));
        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($decorated))) {
            return $this->redirectToRoute('get_all_decorated');
        }

        try {
            $this->em->persist($decorated);
            $this->em->flush();
            $this->addFlash($this->getParameter(self::$successOperation), "Decorado " .
                $decorated->getName() . " editado satisfactoriamente.");
            return $this->redirectToRoute('get_all_decorated');
        } catch (\Exception $exception) {
            $this->addFlash($this->getParameter(self::$failOperation), "El decorado " .
                $decorated->getName() . " no se ha podido editar.");
            return $this->redirectToRoute('get_all_decorated');
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