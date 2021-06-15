<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function CountProducts()
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


/* ******* Catalogue *********** */

    public function GetAllProducts($page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }


    public function GetProductsByZone($zone, $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }



    /* ***************  Get Products by MATERIALS ****************************** */
/*->andWhere('p.setProduct = :val2')
->setParameter('val2', 1)*/
    public function CountProductsByMaterials($materialToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idMaterial = :val')
            ->setParameter('val', $materialToFilter)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function GetProductsByMaterials($materialToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idMaterial = :val')
            ->setParameter('val', $materialToFilter)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by MATERIALS ****************************** */


    /* ***************  Get Products by FORMATS ****************************** */

    public function CountProductsByFormats($formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idFormat IN (:val)')
            ->setParameter('val', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function GetProductsByFormats($formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idFormat IN (:val)')
            ->setParameter('val', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by FORMATS ****************************** */



    /* ***************  Get Products by ZONE and Materials ****************************** */
    public function CountProductsByZoneAndMaterial($zone, $materialToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function GetProductsByZoneAndMaterial($zone, $materialToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by ZONES and Materials ****************************** */



    /* ***************  Get Products by ZONE and FORMATS ****************************** */
    public function CountProductsByZoneAndFormats($zone, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetProductsByZoneAndFormats($zone, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by ZONES and FORMATS ****************************** */


    /* ***************  Get Products by ZONE, MATERIALS and FORMATS ****************************** */
    public function CountProductsByZoneMaterialAndFormats($zone, $materialToFilter, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetProductsByZoneMaterialAndFormats ($zone, $materialToFilter, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by ZONES, MATERIALS and FORMATS ****************************** */


    /* ***************  Get Products by ZONE, MATERIALS and FORMATS ****************************** */
    public function CountProductsByMaterialAndFormats($materialToFilter, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function GetProductsByMaterialsAndFormats ($materialToFilter, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by ZONES, MATERIALS and FORMATS ****************************** */



    /* ***************  Get Products by SET ****************************** */
    public function CountSetProducts()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.setProduct > :val')
            ->setParameter('val', 1)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetSetProducts( $page, $elementsNumber): Paginator
    {

        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.setProduct > :val')
            ->setParameter('val', 1)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }

    /* ***************  End get Products by SET ****************************** */

/* ******* End Catalogue *********** */


/* ********* OUTLET *********** */

    /* ***************  Get all Outlet Products ****************************** */
    public function CountOutletProducts($outlet)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :val')
            ->setParameter('val', $outlet)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetOutletProducts( $page, $elementsNumber): Paginator
    {

        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :val')
            ->setParameter('val', 2)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }

    /* ***************  END Get Outlet Products ****************************** */

    /* ***************  Get Outlet products  by zone ****************************** */
    public function GetOutletProductsByZone($zone, $outlet , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  End Outlet products by zone ****************************** */


    /* ***************  Get Outlet Products by MATERIALS ****************************** */
    /*->andWhere('p.setProduct = :val2')
    ->setParameter('val2', 1)*/
    public function CountOutletProductsByMaterials($materialToFilter, $outlet)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idMaterial = :val')
            ->setParameter('val', $materialToFilter)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function GetOutletProductsByMaterials($materialToFilter , $outlet, $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idMaterial = :val')
            ->setParameter('val', $materialToFilter)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Outlet Products by MATERIALS ****************************** */


    /* ***************  Get Outlet Products by FORMATS ****************************** */

    public function CountOutletProductsByFormats($outlet, $formatsToFilter )
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function GetOutletProductsByFormats($outlet, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Outlet Products by FORMATS ****************************** */



    /* ***************  Get Outlet Products by ZONE and Materials ****************************** */
    public function CountOutletProductsByZoneAndMaterial($outlet,$zone, $materialToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function GetOutletProductsByZoneAndMaterial($outlet,$zone, $materialToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Outlet Products by ZONES and Materials ****************************** */


    /* ***************  Get  Outlet Products by ZONE and FORMATS ****************************** */
    public function CountOutletProductsByZoneAndFormats($outlet,$zone, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetOutletProductsByZoneAndFormats($outlet,$zone, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idFormat IN (:val2)')
            ->setParameter('val2', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Products by ZONES and FORMATS ****************************** */


    /* ***************  Get Outlet Products by MATERIALS and FORMATS ****************************** */
    public function CountOutletProductsByMaterialAndFormats($outlet,$materialToFilter, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function GetOutletProductsByMaterialsAndFormats ($outlet,$materialToFilter, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Outlet Get Products  MATERIALS and FORMATS ****************************** */

    /* ***************  Get Outlet Products by ZONE, MATERIALS and FORMATS ****************************** */
    public function CountOutletProductsByZoneMaterialAndFormats($outlet,$zone, $materialToFilter, $formatsToFilter)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetOutletProductsByZoneMaterialAndFormats ($outlet,$zone, $materialToFilter, $formatsToFilter , $page, $elementsNumber): Paginator
    {
        $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idType = :outlet')
            ->setParameter('outlet', $outlet)
            ->andWhere('p.idZone = :val')
            ->setParameter('val', $zone)
            ->andWhere('p.idMaterial = :val2')
            ->setParameter('val2', $materialToFilter)
            ->andWhere('p.idFormat IN (:val3)')
            ->setParameter('val3', array_values($formatsToFilter))
            ->orderBy('p.id', 'ASC')
            ->getQuery();
        return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  END Get Outlet Products by ZONES, MATERIALS and FORMATS ****************************** */

/* ********* END OUTLET *********** */

    /* ***************  Get Relationated products ****************************** */
    public function CountRelationallyProducts($idFormat)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idFormat >= :val')
            ->setParameter('val', $idFormat)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function GetRelationallyProducts($idFormat)
    {
        return $queryDql = $this->createQueryBuilder('p')
            ->andWhere('p.idFormat >= :val')
            ->setParameter('val', $idFormat)
            ->orderBy('p.idFormat', 'ASC')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->setMaxResults(12)
            ->getResult();
       // return $this->productPagination($queryDql,$page,$elementsNumber);
    }
    /* ***************  End Relationated products ****************************** */

    public function GetProductsByFormatAndZone($format, $zone)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idFormat = :val')
            ->setParameter('val', $format)
            ->andWhere('p.idZone = :val2')
            ->setParameter('val2', $zone)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function productPagination ($dql, $page, $elementsNumber): Paginator
    {
        $paginator = new Paginator($dql);
        $paginator->getQuery()
            ->setFirstResult($elementsNumber * ($page-1))
            ->setMaxResults($elementsNumber);
        return $paginator;

    }

    // /**
    //  * @return Producto[] Returns an array of Producto objects
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
    public function findOneBySomeField($value): ?Producto
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
