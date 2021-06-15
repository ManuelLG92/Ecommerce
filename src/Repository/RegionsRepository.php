<?php

namespace App\Repository;

use App\Entity\Regions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Regions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regions[]    findAll()
 * @method Regions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regions::class);
    }

    // /**
    //  * @return Regions[] Returns an array of Regions objects
    //  */
    /* */
    public function findByCountryId($value)
    {
        return $this->createQueryBuilder('r')
            ->select('r.id','r.name', 'r.code')
            ->andWhere('r.country = :val')
            ->setParameter('val', $value)
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRegionName($value)
    {
        return $this->createQueryBuilder('r')
            ->select('r.id','r.name', 'r.code')
            ->andWhere('r.country = :val')
            ->setParameter('val', $value)
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Regions
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
