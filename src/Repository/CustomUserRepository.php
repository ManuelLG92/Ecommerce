<?php

namespace App\Repository;

use App\Entity\CustomUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomUser[]    findAll()
 * @method CustomUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomUser::class);
    }

    // /**
    //  * @return CustomUser[] Returns an array of CustomUser objects
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
    public function findOneBySomeField($value): ?CustomUser
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
