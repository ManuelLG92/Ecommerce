<?php

namespace App\Repository;

use App\Entity\Decorated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Decorated|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decorated|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decorated[]    findAll()
 * @method Decorated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecoratedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decorated::class);
    }

    // /**
    //  * @return Decorated[] Returns an array of Decorated objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Decorated
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
