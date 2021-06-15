<?php


namespace App\Services;


use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WarehouseManager
{
    private $em;
    private $warehouseRepository;
    private $validator;

    function __construct(EntityManagerInterface  $entityManager,
                         WarehouseRepository $warehouseRepository,
                         ValidatorInterface  $validator)
    {
        $this->em = $entityManager;

        $this->warehouseRepository = $warehouseRepository;
        $this->validator = $validator;
    }

    public function ValidateWarehouseFields(Warehouse $warehouse)
    {
        return $this->validator->validate($warehouse);
    }

    public function AssingDataToWarehouse (Request  $request) :?Warehouse
    {
        $warehouse = new Warehouse();
        $warehouse->setName(trim($request->request->get('name')));
        return $warehouse;
    }

    public function AssingDataToExistingWarehouse (Warehouse $warehouse,Request  $request) :?Warehouse
    {
        $warehouse->setName(trim($request->request->get('name')));
        return $warehouse;
    }

    public function CreateOrUpdateWarehouse(Warehouse $warehouse) :?Warehouse
    {
        try {
            $this->em->persist($warehouse);
            $this->em->flush();
            return $warehouse;
        } catch (\Exception $exception){
            return null;
        }
    }

    public function DeleteCategory(Warehouse $warehouse) :?Warehouse
    {
        try {
            $this->em->remove($warehouse);
            $this->em->flush();
            return $warehouse;
        } catch (\Exception $exception){
            return null;
        }
    }

    public function GetWarehouse(Request  $request): ?Warehouse
    {
        $warehouseId = intval($request->request->get('idWarehouse'));
        return $this->em->getRepository(Warehouse::class)->find($warehouseId);


    }





}