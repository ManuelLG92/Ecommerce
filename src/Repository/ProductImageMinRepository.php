<?php

namespace App\Repository;

use App\Entity\ProductImageMin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductImageMin|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImageMin|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImageMin[]    findAll()
 * @method ProductImageMin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImageMinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImageMin::class);
    }

    // /**
    //  * @return ProductImageMin[] Returns an array of ProductImageMin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductImageMin
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
