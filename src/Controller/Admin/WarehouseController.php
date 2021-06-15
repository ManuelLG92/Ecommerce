<?php

namespace App\Controller\Admin;

use App\Entity\Warehouse;
use App\Form\AlmacenType;
use App\Repository\WarehouseRepository;
use App\Services\ZoneManager;
use App\Services\GlobalManager;
use App\Services\WarehouseManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/warehouse")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class WarehouseController extends AbstractController
{

    private EntityManagerInterface $em;
    private WarehouseManager $warehouseManager;
    private GlobalManager $globalManager;
    private $message;


    public static $successOperation = "success_message";
    public static $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager, GlobalManager $globalManager, WarehouseManager $warehouseManager)
    {

        $this->em = $entityManager;
        $this->warehouseManager = $warehouseManager;
        $this->globalManager = $globalManager;
    }

    /**
     * @Route("/", name="get_all_warehouses", methods={"GET"})
     */
    public function index(): Response
    {
        $warehouses = $this->em->getRepository(Warehouse::class)->findAll();
        $warehouse = new Warehouse();
        return $this->render('admin/warehouse/index.html.twig', [
            'title' => 'Almacenes',
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * @Route ("/json",name="get_all_warehouses_json", methods={"GET"})
     */
    public function GetWarehousesJson(): Response
    {
        return $this->json(['warehouses' => $this->em->getRepository(Warehouse::class)->findAll()]);
    }

    /**
     * @Route("/create", name="create_warehouse_get", methods={"GET"})
     */
    public function CreateWarehouseGet(Request $request): Response
    {
        $warehouse = new Warehouse();
        return $this->render('admin/warehouse/new.html.twig', [
            'title' => 'Crear almacen',
            'warehouse' => $warehouse,

        ]);
    }

    /**
     * @Route("/create", name="create_warehouse_post", methods={"POST"})
     */
    public function CreateWarehousePost(Request $request): Response
    {
        $warehouse = $this->warehouseManager->AssingDataToWarehouse($request);

        $errorWarehouseValidation = $this->warehouseManager->ValidateWarehouseFields($warehouse);

        if ($this->globalManager->CheckErrorsOnValidation($errorWarehouseValidation)){
            return  $this->redirectToRoute('get_all_warehouses');
        }

        if ($this->warehouseManager->CreateOrUpdateWarehouse($warehouse)){
            $this->message = "Almacen " . $warehouse->getName() . " Creado satisfactoriamente.";
            $this->addFlash($this->getParameter(self::$successOperation),$this->message);
            return $this->redirectToRoute('get_all_warehouses');
        }

        $this->message = "Ha habido un error creando el almacen " . $warehouse->getName() . ". Intentelo mas tarde.";
        $this->addFlash($this->get(self::$failOperation),$this->message);
        return $this->redirectToRoute('get_all_warehouses');

    }




    /**
     * @Route ("/edit/{id<\d+>}",name="edit_warehouse_get", methods={"GET"})
     */
    public function EditWarehouseGet(int $id): Response
    {
        $warehouse = $this->em->getRepository(Warehouse::class)->find($id);

        if (!$warehouse){
            $this->message = "El almacen #" . $id . " no ha sido encontrado.";
            $this->addFlash($this->getParameter(self::$failOperation),$this->message);
            return $this->redirectToRoute("get_all_warehouses");
        }
        return $this->render('admin/warehouse/edit.html.twig', [
            'title' => 'Editar Almacen',
            'warehouse' => $warehouse,
        ]);
    }
    /**
     * @Route ("/edit",name="edit_warehouse_put", methods={"PUT"})
     */
    public function EditCategoryPost(Request $request) :Response
    {

        $id = $request->request->get('idWarehouse');

        $warehouse = $this->warehouseManager->GetWarehouse($request);

        if (!$warehouse){
            $this->addFlash($this->getParameter(self::$failOperation),"El almacen #" . $id . " no ha sido encontrado.");
            return $this->redirectToRoute('get_all_warehouses');
        }

        $warehouse = $this->warehouseManager->AssingDataToExistingWarehouse($warehouse, $request);

        $errorsValidation = $this->warehouseManager->ValidateWarehouseFields($warehouse);

        if ($this->globalManager->CheckErrorsOnValidation($errorsValidation)){
            return  $this->redirectToRoute('edit_warehouse_get', ['id'=> intval($id)]);
        }

        //$categoriaEdited = $this->warehouseManager->CreateOrUpdateWarehouse($warehouse);

        if (!$this->warehouseManager->CreateOrUpdateWarehouse($warehouse)){
            $this->addFlash($this->getParameter(self::$failOperation),"Ha habido un problema editando el almacen " .
                $warehouse->getName() . ". Intentelo mas tarde.");
            return  $this->redirectToRoute('edit_warehouse_get', ['id'=> $id]);
        }
        $this->addFlash($this->getParameter(self::$successOperation),"Almacen " . $warehouse->getName() . " editado exitosamente.");
        return $this->redirectToRoute('get_all_warehouses');

    }

    /**
     * @Route("/delete/{id<\d+>}", name="warehouse_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Warehouse $almacen): Response
    {
        if ($this->isCsrfTokenValid('delete' . $almacen->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($almacen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('get_all_warehouses');
    }


}
