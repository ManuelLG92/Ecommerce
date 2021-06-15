<?php

namespace App\Repository;

use App\Entity\ProductDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductDTO|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDTO|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDTO[]    findAll()
 * @method ProductDTO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDTORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDTO::class);
    }

    // /**
    //  * @return ProductDTO[] Returns an array of ProductDTO objects
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
    public function findOneBySomeField($value): ?ProductDTO
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
