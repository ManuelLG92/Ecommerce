<?php


namespace App\Services;


use App\Entity\Zone;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ZoneManager
{
    private $em;
    private $categoryRepository;
    private $validator;

    function __construct(EntityManagerInterface  $entityManager,
                         ZoneRepository $categoryRepository,
                         ValidatorInterface  $validator)
    {
        $this->em = $entityManager;
        $this->categoryRepository = $categoryRepository;
        $this->validator = $validator;
    }

    public function ValidateZoneFields(Zone  $category)
    {
        return $this->validator->validate($category);
    }

    public function AssingDataToZone(Request  $request) :?Zone
    {
        $category = new Zone();
        $category->setName(trim($request->request->get('name')));
        return $category;
    }

    public function CreateOrUpdateZone(Zone $category) :?Zone
    {
        try {
            $this->em->persist($category);
            $this->em->flush();
            return $category;
        } catch (\Exception $exception){
            return null;
        }
    }

    public function DeleteZone(Zone $category) :?Zone
    {
        try {
            $this->em->remove($category);
            $this->em->flush();
            return $category;
        } catch (\Exception $exception){
            return null;
        }
    }

    public function GetZone(Request  $request): ?Zone
    {
        $categoryId = intval($request->request->get('idZone'));
        return $this->em->getRepository(Zone::class)->find($categoryId);


    }





}