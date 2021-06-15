<?php

namespace App\Controller\Site;


use App\Entity\Material;
use App\Entity\Product;
use App\Entity\Zone;
use App\Services\SiteManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductFilterController extends AbstractController
{
    private $ELEMENTS_BY_PAGE = 6;
    private $em;
    private $siteManager;
    function __construct(EntityManagerInterface $em, SiteManager  $siteManager)
    {
        $this->em = $em;
        $this->siteManager= $siteManager;
    }


    /**
     * @Route ("/product/{id<\d+>}", name="site_product_show", methods={"GET"})
     */
    public function product(Product $product): Response
    {
        //$coverImage = $this->em->getRepository(ProductImageCover::class)->findOneBy(['idProduct' => $product->getId()]);
        $relationallyProducts = $this->em->getRepository(Product::class)->GetRelationallyProducts($product->getIdFormat()->getId());
        return $this->render('site/product/_product_show.html.twig', [
            'title' => 'Producto',
            'product' => $product,
            'relationally' => $relationallyProducts,
            // 'image' => $coverImage,
        ]);
    }

    /**
     * @Route ("/product/zone/{id<\d+>}/{page<\d+>}",
     *     name="products_by_zone",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsByZone(int $id, int $page): Response
    {
        $zone = $this->em->getRepository(Zone::class)->find($id);
        if (!$zone ){
            $this->addFlash('fail','No se han encontrado la zona.');
            return $this->redirectToRoute('home');
        }
        $products = $this->em->getRepository(Product::class)->findBy(['idZone'=>$id]);
        $countProductsByZone = count($products);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByZone);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_zone', ['id' => $id , 'page' => 1]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_zone', ['id' => $id , 'page' => $numberPages]);
        }


        $products = $this->em->getRepository(Product::class)->GetProductsByZone($id,$page, $this->ELEMENTS_BY_PAGE);
        list($zones, $materials, $formats) = $this->siteManager->getZonesMaterialsAndFormats();
        $selectedMaterials = 0;
        $selectedFormats = [];

        return $this->render('site/product/products_by_zone.html.twig', [
            'title' => ucfirst($zone->getName()),
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZone,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materials,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $selectedMaterials,
            'selectedFormats' => $selectedFormats,

        ]);

    }

    /**
     * @Route ("/product/zones/{zone<\d+>}/materials/{material<\d+>}/{page<\d+>}",
     *     name="product_by_zones_material",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsByZoneAndMaterials(Zone $zone, Material $material, int $page): Response
    {

        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);

        $countProductsByZoneAndMaterial = $this->em->getRepository(Product::class)
                ->CountProductsByZoneAndMaterial($zone->getId(), $materialToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByZoneAndMaterial);

        if ($page < 1 ) {
            return $this->redirectToRoute('product_by_zones_material',
                [
                    'zone' => $zone->getId(),'material' => $materialToFilter ,'page' => 1
                ]);
        }

        if ($page > $numberPages ) {
            return $this->redirectToRoute('product_by_zones_material',
                [
                    'zone' => $zone->getId() ,'material' => $materialToFilter ,'page' => $numberPages
                ]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetProductsByZoneAndMaterial($zone->getId(),$materialToFilter,$page,$this->ELEMENTS_BY_PAGE);

        list($zones, $materialsList, $formats) = $this->siteManager->getZonesMaterialsAndFormats();


        $selectedFormats = [];
        return $this->render('site/product/products_by_zone_material.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZoneAndMaterial,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materialsList,
            'materialsFilter' => $materialToFilter,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $selectedFormats,

        ]);
    }

    /**
     * @Route ("/product/zones/{zone<\d+>}/formats/{formats}/{page<\d+>}",
     *     name="products_by_zones_formats",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsByZoneAndFormats(Zone $zone, $formats, int $page): Response
    {

        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);

        $countProductsByZoneAndFormats = $this->em->getRepository(Product::class)->CountProductsByZoneAndFormats($zone->getId(), $formatsToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByZoneAndFormats);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_zones_formats', ['zone' => $zone->getId(),'formats' => $formats ]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_zones_formats', ['zone' => $zone->getId() ,'formats' => $formats ,'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetProductsByZoneAndFormats($zone->getId(),$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);

        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();
        $materialToFilter = 0;

     //   $selectedFormats = [];
        return $this->render('site/product/products_by_zone_format.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZoneAndFormats,
            'zones' => $zones,
            'formats' => $formatsList,
            'materials' => $materialsList,
            'formatsFilter' => $formats,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $formatsToFilter,

        ]);
    }

    /**
     * @Route ("/product/all-zones/materials/{material<\d+>}/{page<\d+>}",
     *     name="products_by_materials",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsByMaterials(Material $material ,int $page): Response
    {

      //  $material = $this->em->getRepository(Material::class)->find($material);
        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);
        $productsCount = $this->em->getRepository(Product::class)->CountProductsByMaterials($materialToFilter);
        list($zones, $materialsList, $formats) = $this->siteManager->getZonesMaterialsAndFormats();

        $numberPages = $this->siteManager->GetPageNumbers($productsCount);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_materials',
                [
                    'material' => $materialToFilter
                ]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_materials',
                [
                    'material' => $materialToFilter , 'page' => $numberPages
                ]);
        }

        $products = $this->em->getRepository(Product::class)->GetProductsByMaterials($materialToFilter, $page,$this->ELEMENTS_BY_PAGE);

        $selectedFormats = [];
        return $this->render('site/product/products_by_materials.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $productsCount,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materialsList,
            'materialFilter' => $materialToFilter,
            'selectedZone' => 0 ,
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $selectedFormats,

        ]);

/*        return $this->render('site/product/products_by_zone_format.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZoneAndFormats,
            'zones' => $zones,
            'formats' => $formatsList,
            'materials' => $materialsList,
            'formatsFilter' => $formats,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $formatsToFilter,

        ]);*/

    }

    /**
     * @Route ("/product/all-zones/format/{formats}/{page<\d+>}",
     *     name="products_by_formats",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsByFormats($formats,int $page): Response
    {
        //  $material = $this->em->getRepository(Material::class)->find($material);
        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $productsCount = $this->em->getRepository(Product::class)->CountProductsByFormats($formatsToFilter);
        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();

        $numberPages = $this->siteManager->GetPageNumbers($productsCount);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_formats', ['formats' => $formats]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_formats', ['formats' => $formats , 'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)->GetProductsByFormats($formatsToFilter, $page,$this->ELEMENTS_BY_PAGE);

        $materialToFilter = 0;

        return $this->render('site/product/products_by_formats.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $productsCount,
            'zones' => $zones,
            'formats' => $formatsList,
            'materials' => $materialsList,
            'formatsToFilter' => $formats,
            'selectedZone' => 0 ,
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $formatsToFilter,

        ]);

    }

    /**
     * @Route ("/product/zones/{zone<\d+>}/materials/{material}/formats/{formats}/{page<\d+>}",
     *     name="products_by_zone_materials_format",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function ProductsByZoneMaterialsAndFormat(Zone $zone, Material $material, $formats, int $page)
    {
        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);

        $countProductsByZoneMaterialAndFormat = $this->em->getRepository(Product::class)
            ->CountProductsByZoneMaterialAndFormats($zone->getId(), $materialToFilter, $formatsToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByZoneMaterialAndFormat);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_zone_materials_format',
                ['zone' => $zone->getId(),'materials' => $materialToFilter ,'formats' => $formats]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_zone_materials_format',
                ['zone' => $zone->getId() ,'materials' => $materialToFilter ,'formats' => $formats,'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetProductsByZoneMaterialAndFormats($zone->getId(),$materialToFilter,$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);

        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();

        return $this->render('site/product/products_by_zone_materials_formats.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZoneMaterialAndFormat,
            'zones' => $zones,
            'formats' => $formatsList,
            'materials' => $materialsList,
            'materialsFilter' => $materialToFilter,
            'formatsFilter' => $formats,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $formatsToFilter,

        ]);
    }

    /**
     * @Route ("/product/all-zones/materials/{material<\d+>}/formats/{formats}/{page<\d+>}",
     *     name="products_by_materials_format",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function ProductsByMaterialsAndFormat(Material $material, $formats, int $page)
    {

       // dd('stop');

        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);


        $countProductsByMaterialAndFormat = $this->em->getRepository(Product::class)
            ->CountProductsByMaterialAndFormats($materialToFilter, $formatsToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByMaterialAndFormat);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_materials_format',
                ['material' => $material ,'formats' => $formats]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_materials_format',
                ['material' => $material ,'formats' => $formats,'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetProductsByMaterialsAndFormats($materialToFilter,$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);
        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();

        return $this->render('site/product/products_by_materials_formats.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByMaterialAndFormat,
            'zones' => $zones,
            'formats' => $formatsList,
            'materials' => $materialsList,
            'materialsFilter' => $materialToFilter,
            'formatsFilter' => $formats,
            'selectedZone' => 0,
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $formatsToFilter,

        ]);
    }

    /**
     * @Route ("/filter-by", name="filter_by",
     *     methods={"POST"})
     */
    public function FilterBy(Request $request)
    {
        //dd($request->request->all());
        [$zone, $material, $formats]  = $this->siteManager->getZoneMaterialAndFormatFromRequest($request);

        //materials ? $material = implode("-", $materials) : $material = '';
        $formats ? $formatsImploded = implode("-", $formats) : $formatsImploded = '';

        [$materialsLength, $formatsImplodedLength, $zoneLength] = $this->siteManager->getZoneMaterialandFormatLengthFromRequest($material, $formatsImploded, $zone);


        //If doesnt receive any zone to filter
        if ($zoneLength<1){
            return $this->redirectToRoute('catalogue');
        }

        //Filter by ONLY by ZONE
        if ($material == 0 && $formatsImplodedLength<1 ){
           if ($zone == 0) {
                return $this->redirectToRoute('catalogue');
            } /* else if ($zone==18){
                return $this->redirectToRoute('set_products');
            }*/else {
                return $this->redirectToRoute('products_by_zone', ['id'=> $zone]);
           }
        }

        //Filter by Zone and Materials
        if ($material>0 && $formatsImplodedLength<1 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_by_materials',
                    [
                        'material' => $material
                    ]);
            } else {
                return $this->redirectToRoute('product_by_zones_material',
                    [
                        'zone'=> $zone, 'material' => $material
                    ]);
            }
        }

        //Filter by Zone and Formats
        if ($material == 0 && $formatsImplodedLength>0 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_by_formats',
                    [
                        'formats' => $formatsImploded
                    ]);
            } else {
                //dd('stop');
                return $this->redirectToRoute('products_by_zones_formats',
                    [
                        'zone'=> $zone, 'formats' => $formatsImploded
                    ]);
            }
        }


        //Filter by Zone , Material and Format
        if ($materialsLength>0 && $formatsImplodedLength>0 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_by_materials_format',
                    ['material' => $material ,'formats' => $formatsImploded]);
            } else {
                return $this->redirectToRoute('products_by_zone_materials_format',
                    ['zone'=> $zone, 'material' => $material ,'formats' => $formatsImploded]);
            }
        }

        return $this->redirectToRoute('catalogue');

    }



}

