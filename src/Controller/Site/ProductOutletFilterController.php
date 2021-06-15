<?php


namespace App\Controller\Site;


use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\Zone;
use App\Services\SiteManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ProductOutletController
 * @package App\Controller\Admin
 * @Route ("/product/outlet")
 */
class ProductOutletFilterController extends AbstractController


{

    private $ELEMENTS_BY_PAGE = 4;
    private $em;
    private $siteManager;
    function __construct(EntityManagerInterface $em, SiteManager  $siteManager)
    {
        $this->em = $em;
        $this->siteManager= $siteManager;
    }

    /**
     * @Route ("/zone/{zone<\d+>}/{page<\d+>}",
     *     name="products_outlet_by_zone",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsOutletByZone(Zone $zone, int $page): Response
    {

        //dd($this->getOutletId());
        $products = $this->em->getRepository(Product::class)->findBy(['idType' => $this->getOutletId(),'idZone' => $zone->getId()]);
        $countProductsOutletByZone = count($products);
        //dd($zone->getId());
        $numberPages = $this->siteManager->GetPageNumbers($countProductsOutletByZone);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_zone', ['id' => $zone->getId() , 'page' => 1]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_zone', ['id' => $zone->getId() , 'page' => $numberPages]);
        }


        $products = $this->em->getRepository(Product::class)
            ->GetOutletProductsByZone($zone->getId(),$this->getOutletId(),$page, $this->ELEMENTS_BY_PAGE);
        list($zones, $materials, $formats) = $this->siteManager->getZonesMaterialsAndFormats();
        $selectedMaterials = 0;
        $selectedFormats = [];

        return $this->render('site/product/outlet/products_outlet_by_zone.html.twig', [
            'title' => ucfirst($zone->getName()),
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsOutletByZone,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materials,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $selectedMaterials,
            'selectedFormats' => $selectedFormats,
            'zone' => $zone->getId(),

        ]);

    }

    /**
     * @Route ("/zone/{zone<\d+>}/material/{material<\d+>}/{page<\d+>}",
     *     name="product_outlet_by_zones_material",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsOutletByZoneAndMaterial(Zone $zone, Material $material, int $page): Response
    {

        $materialToFilter = $material->getId();

        $countProductsByZoneAndMaterial = $this->em->getRepository(Product::class)
            ->CountOutletProductsByZoneAndMaterial($this->getOutletId(),$zone->getId(), $materialToFilter);
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
            ->GetOutletProductsByZoneAndMaterial($this->getOutletId(),$zone->getId(),$materialToFilter,$page,$this->ELEMENTS_BY_PAGE);

        [$zones, $materialsList, $formats] = $this->siteManager->getZonesMaterialsAndFormats();

        $selectedFormats = [];
        return $this->render('site/product/outlet/products_outlet_by_zone_material.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countProductsByZoneAndMaterial,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materialsList,
            'materialFilter' => $materialToFilter,
            'selectedZone' => $zone->getId(),
            'selectedMaterials' => $materialToFilter,
            'selectedFormats' => $selectedFormats,

        ]);
    }

    /**
     * @Route ("/zone/{zone<\d+>}/formats/{formats}/{page<\d+>}",
     *     name="product_outlet_by_zone_format",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsOutletByZoneAndFormats(Zone $zone, $formats, int $page): Response

    {

        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);

        $countProductsByZoneAndFormats = $this->em->getRepository(Product::class)
            ->CountOutletProductsByZoneAndFormats($this->getOutletId(),$zone->getId(), $formatsToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countProductsByZoneAndFormats);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_zones_formats', ['zone' => $zone->getId(),'formats' => $formats ]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_zones_formats', ['zone' => $zone->getId() ,'formats' => $formats ,'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetOutletProductsByZoneAndFormats($this->getOutletId(),$zone->getId(),$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);

        [$zones, $materialsList, $formatsList] = $this->siteManager->getZonesMaterialsAndFormats();
        $materialToFilter = 0;

        return $this->render(
            'site/product/outlet/products_outlet_by_zone_format.html.twig', [
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
     * @Route ("/all-zones/material/{material<\d+>}/{page<\d+>}",
     *     name="products_outlet_by_material",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsOutletByMaterial(Material $material ,int $page): Response
    {

        //  $material = $this->em->getRepository(Material::class)->find($material);
        $materialToFilter = $material->getId();
        $productsCount = $this->em->getRepository(Product::class)->CountOutletProductsByMaterials($materialToFilter, $this->getOutletId());
        [$zones, $materialsList, $formats] = $this->siteManager->getZonesMaterialsAndFormats();

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

        $products = $this->em->getRepository(Product::class)
            ->GetOutletProductsByMaterials($materialToFilter, $this->getOutletId(), $page,$this->ELEMENTS_BY_PAGE);

        $selectedFormats = [];
        return $this->render('site/product/outlet/products_outlet_by_material.html.twig', [
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
     * @Route ("/all-zones/format/{formats}/{page<\d+>}",
     *     name="products_outlet_by_formats",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function productsOutletByFormats($formats ,int $page): Response
    {

        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $productsCount = $this->em->getRepository(Product::class)->CountOutletProductsByFormats($this->getOutletId(),$formatsToFilter);
        [$zones, $materialsList, $formatsList] = $this->siteManager->getZonesMaterialsAndFormats();

        $numberPages = $this->siteManager->GetPageNumbers($productsCount);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_outlet_by_formats', ['formats' => $formats]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_outlet_by_formats', ['formats' => $formats , 'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)->GetOutletProductsByFormats($this->getOutletId(),$formatsToFilter, $page,$this->ELEMENTS_BY_PAGE);

        $materialToFilter = 0;

        return $this->render('site/product/outlet/products_outlet_by_formats.html.twig', [
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
     * @Route ("/all-zones/material/{material<\d+>}/formats/{formats}/{page<\d+>}",
     *     name="products_outlet_by_materials_format",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function ProductsByMaterialAndFormat(Material $material, $formats, int $page)
    {

        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);


        $countOutletProductsByMaterialAndFormat = $this->em->getRepository(Product::class)
            ->CountOutletProductsByMaterialAndFormats($this->getOutletId(), $materialToFilter, $formatsToFilter);
        $numberPages = $this->siteManager->GetPageNumbers($countOutletProductsByMaterialAndFormat);

        if ($page < 1 ) {
            return $this->redirectToRoute('products_by_materials_format',
                ['material' => $material ,'formats' => $formats]);
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('products_by_materials_format',
                ['material' => $material ,'formats' => $formats,'page' => $numberPages]);
        }

        $products = $this->em->getRepository(Product::class)
            ->GetOutletProductsByMaterialsAndFormats($this->getOutletId(),$materialToFilter,$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);
        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();

        return $this->render('site/product/outlet/products_outlet_by_material_formats.html.twig', [
            'title' => 'Estilker',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countOutletProductsByMaterialAndFormat,
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
     * @Route ("/zone/{zone<\d+>}/material/{material}/formats/{formats}/{page<\d+>}",
     *     name="products_outlet_by_zone_material_formats",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function ProductsByZoneMaterialAndFormats(Zone $zone, Material $material, $formats, int $page)
    {
        $formatsToFilter = $this->siteManager->getFormatsToFilterFromRequest($formats);
        $materialToFilter = $this->siteManager->getMaterialIdToFilter($material);

        $countProductsByZoneMaterialAndFormat = $this->em->getRepository(Product::class)
            ->CountOutletProductsByZoneMaterialAndFormats($this->getOutletId(), $zone->getId(), $materialToFilter, $formatsToFilter);
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
            ->GetOutletProductsByZoneMaterialAndFormats($this->getOutletId(),$zone->getId(),$materialToFilter,$formatsToFilter,$page,$this->ELEMENTS_BY_PAGE);

        list($zones, $materialsList, $formatsList) = $this->siteManager->getZonesMaterialsAndFormats();

        return $this->render(
            'site/product/outlet/products_outlet_by_zone_materials_formats.html.twig', [
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

        ]
        );
    }

    /**
     * @Route ("/filter-by/outlet", name="filter_by_outlet", methods={"POST"})
     */
    public function FilterOutletProducts(Request $request): RedirectResponse
    {
        //dd($request->request->all());

        [$zone, $material, $formats] = $this->siteManager->getZoneMaterialAndFormatFromRequest($request);

        //materials ? $material = implode("-", $materials) : $material = '';
        $formatsImploded = $this->siteManager->getFormatsImploded($formats);

        [$materialsLength, $formatsImplodedLength, $zoneLength] = $this->siteManager
            ->getZoneMaterialandFormatLengthFromRequest($material, $formatsImploded, $zone);

        //If doesnt receive any zone to filter
        if ($zoneLength<1){
            return $this->redirectToRoute('outlet');
        }

        //Filter by ONLY by ZONE
        if ($material == 0 && $formatsImplodedLength<1 ){
            if ($zone == 0) {
                return $this->redirectToRoute('outlet');
            } /* else if ($zone==18){
                return $this->redirectToRoute('set_products');
            }*/else {
                return $this->redirectToRoute('products_outlet_by_zone', ['zone'=> $zone]);
            }
        }

        //Filter by Zone and Materials
        if ($material>0 && $formatsImplodedLength<1 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_outlet_by_material',
                    [
                        'material' => $material
                    ]);
            } else {
                return $this->redirectToRoute('product_outlet_by_zones_material',
                    [
                        'zone'=> $zone, 'material' => $material
                    ]);
            }
        }

        //Filter by Zone and Formats
        if ($material == 0 && $formatsImplodedLength>0 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_outlet_by_formats',
                    [
                        'formats' => $formatsImploded
                    ]);
            } else {
                return $this->redirectToRoute('product_outlet_by_zone_format',
                    [
                        'zone'=> $zone, 'formats' => $formatsImploded
                    ]);
            }
        }


        //Filter by Zone , Material and Format
        if ($materialsLength>0 && $formatsImplodedLength>0 ){
            if ($zone == 0) {
                return $this->redirectToRoute('products_outlet_by_materials_format',
                    ['material' => $material ,'formats' => $formatsImploded]);
            } else {
                return $this->redirectToRoute('products_outlet_by_zone_material_formats',
                    ['zone'=> $zone, 'material' => $material ,'formats' => $formatsImploded]);
            }
        }/**/


    }


    public function getOutletId(): ?int
    {
        return $this->em->getRepository(ProductType::class)->findOneBy(['name' => 'Outlet'])->getId();
    }





}