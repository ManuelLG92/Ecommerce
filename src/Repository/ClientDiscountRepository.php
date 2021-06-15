<?php

namespace App\Repository;

use App\Entity\ClientDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientDiscount[]    findAll()
 * @method ClientDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientDiscount::class);
    }

    // /**
    //  * @return ClientDiscount[] Returns an array of ClientDiscount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientDiscount
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
