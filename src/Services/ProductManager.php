<?php


namespace App\Services;


use App\Entity\Brand;
use App\Entity\CartProductListDTO;
use App\Entity\Countries;
use App\Entity\Currency;
use App\Entity\Decorated;
use App\Entity\Format;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductDTO;
use App\Entity\ProductImageMin;
use App\Entity\ProductType;
use App\Entity\ProductUse;
use App\Entity\StockProduct;
use App\Entity\Warehouse;
use App\Entity\Zone;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;


class ProductManager

{
    private $em;
    private $productRepository;
    private $logger;

    function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository,
                        LoggerInterface $logger)
    {
        $this->em = $entityManager;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /* --------------- Product section ------------------------- */
    public function GetAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function AssingDataToNewProduct(Request $request): ?Product
    {

        $product = new Product();
        return $this->AssingDataToProduct($product, $request);
    }

    public function AssignDataToProductShow(Product $product): ?ProductDTO
    {
        $productDtoShow = new ProductDTO();
        $arrayWarehouseNameAndStock = [];
        try {

            $stockByWarehouse = $this->em->getRepository(StockProduct::class)
                ->findWarehousesByProductId($product->getId());

            foreach ($stockByWarehouse as $singleWarehouse) {

                if (!$singleWarehouse){
                   continue;
                }

                $arrayWarehouseNameAndStock[] = ['warehouse' => $this->em->getRepository(Warehouse::class)
                    ->find($singleWarehouse->getIdWarehouse())->getName(), 'stock' => $singleWarehouse->getStock()];
            }
        } catch (\Exception $exception) {
            $this->logger->error("An error has ocurred getting some warehouse by product id = " . $product->getId() . ", name = " . $product->getName() . ". Exception: " . $exception);
        }
        try {


            $productDtoShow
                ->setId($product->getId())
                ->setName($product->getName())
                ->setReference($product->getReference())
                ->setFormat($this->em->getRepository(Format::class)->find($product->getIdFormat())->getName())
                ->setProductSet($product->getSetProduct())
                ->setZone($this->em->getRepository(Zone::class)->find($product->getIdZone())->getName())
                ->setProductUse($this->em->getRepository(ProductUse::class)->find($product->getIdUse())->getName())
                ->setProductType($this->em->getRepository(ProductType::class)->find($product->getIdType())->getName())
                ->setDecorated($this->em->getRepository(Decorated::class)->find($product->getIdDecorated())->getName())
                ->setMaterial($this->em->getRepository(Material::class)->find($product->getIdMaterial())->getName())
                ->setCurrency($this->em->getRepository(Currency::class)->find($product->getIdCurrency())->getName())
                ->setPrice($product->getPrice())
                ->setDiscount($product->getDiscount())
                ->setStock($arrayWarehouseNameAndStock)
                ->setImageAmbientUrl($product->getImageAmbient())
                ->setImageCoverUrl($product->getImageCover())
                ->setMinImagesUrl($this->em->getRepository(ProductImageMin::class)->findBy(['idProduct' => $product->getId()]))
                ->setBox($product->getBox())
                ->setCountry($this->em->getRepository(Countries::class)->find($product->getCountry())->getCountryName())
                ->setBrand($product->getBrand()->getName());
        } catch (\Exception $exception) {
            $this->logger->error("An error has ocurred retrieving product : id = " . $product->getId() . ", name = " . $product->getName() . ". Exception: " . $exception);
        }

        return $productDtoShow;


    }
   /* public
    function GetProductAndAssignData(Request $request, int $idProduct): ?Product
    {

        $product = $this->em->getRepository(Product::class)->find($idProduct);
        if ($product) {
            return $this->AssingDataToProduct($product, $request);
        }

        return $product;

    }*/

    public function GetExistingProductAndAssignData(Request $request, int $idProduct): ?Product
    {

        $product = $this->em->getRepository(Product::class)->find($idProduct);
        if ($product) {
            return $this->AssingDataToExistingProduct($product, $request);
        }

        return $product;

    }

    /**
     * @param object|null $product
     * @param Request $request
     * @return object|null
     */
    public function AssingDataToProduct(Product $product, Request $request): ?Product
    {
        try {
        $product->setIdDecorated($this->em->getRepository(Decorated::class)->find(intval(trim($request->request->get('decorated')))))
            ->setName(trim($request->request->get('name')))
            ->setReference(trim($request->request->get('reference')))
            ->setIdFormat($this->em->getRepository(Format::class)->find(intval(trim($request->request->get('format')))))
            ->setSetProduct(intval(trim($request->request->get('setQuantity'))))
            ->setBox((intval(trim($request->request->get('box')))))
            ->setIdUse($this->em->getRepository(ProductUse::class)->find(intval(trim($request->request->get('use')))))
            ->setIdZone($this->em->getRepository(Zone::class)->find(intval(trim($request->request->get('zone')))))
            ->setIdMaterial($this->em->getRepository(Material::class)->find(intval(trim($request->request->get('material')))))
            ->setIdType($this->em->getRepository(ProductType::class)->find(intval(trim($request->request->get('type')))))
            ->setIdCurrency($this->em->getRepository(Currency::class)->find(intval(trim($request->request->get('currency')))))
            ->setPrice(floatval($request->request->get('price')))
            ->setDiscount(intval(trim($request->request->get('discount'))))
            ->setImageCover($request->files->get('cover'))
            ->setImageAmbient($request->files->get('ambient'))
            ->setCountry($this->em->getRepository(Countries::class)->find(intval(trim($request->request->get('country')))))
            ->setBrand($this->em->getRepository(Brand::class)->find(intval(trim($request->request->get('brand')))));
        //dd($product);
        return $product;
        } catch (\Exception $exception){
            $this->logger->error('Has been ocurred an exeption trying to assign data to a product from request. Exception: '. $exception);
            return new Product();
        }
    }

    public function AssingDataToExistingProduct(Product $product, Request $request): ?Product
    {
        try {
            $product->setIdDecorated($this->em->getRepository(Decorated::class)->find(intval(trim($request->request->get('decorated')))))
                ->setName(trim($request->request->get('name')))
                ->setReference(trim($request->request->get('reference')))
                ->setIdFormat($this->em->getRepository(Format::class)->find(intval(trim($request->request->get('format')))))
                ->setSetProduct(intval(trim($request->request->get('setQuantity'))))
                ->setBox((intval(trim($request->request->get('box')))))
                ->setIdUse($this->em->getRepository(ProductUse::class)->find(intval(trim($request->request->get('use')))))
                ->setIdZone($this->em->getRepository(Zone::class)->find(intval(trim($request->request->get('zone')))))
                ->setIdMaterial($this->em->getRepository(Material::class)->find(intval(trim($request->request->get('material')))))
                ->setIdType($this->em->getRepository(ProductType::class)->find(intval(trim($request->request->get('type')))))
                ->setIdCurrency($this->em->getRepository(Currency::class)->find(intval(trim($request->request->get('currency')))))
                ->setPrice(floatval($request->request->get('price')))
                ->setDiscount(intval(trim($request->request->get('discount'))))
                ->setCountry($this->em->getRepository(Countries::class)->find(intval(trim($request->request->get('country')))))
                ->setBrand($this->em->getRepository(Brand::class)->find(intval(trim($request->request->get('brand')))));
            //dd($product);
            return $product;
        } catch (\Exception $exception){
            $this->logger->error('Has been ocurred an exeption trying to assign data to a product from request. Exception: '. $exception);
            return new Product();
        }
    }

    public function GetRelationesProducts(Product $product)
    {

    }
    /* --------------- End product section ---------------------- */


    /* --------------- Product DTO section ------------------------- */
    public function AssingDataToDTOarray(array $products): array
    {
        $dtoArray = [];

        foreach ($products as $product) {

            $productDto = new ProductDTO();
            $arrayWarehouseNameAndStock = [];
            try {

                $stockByWarehouse = $this->em->getRepository(StockProduct::class)
                    ->findWarehousesByProductId($product->getId());

                foreach ($stockByWarehouse as $singleWarehouse) {
                    $arrayWarehouseNameAndStock[] = ['warehouse' => $this->em->getRepository(Warehouse::class)
                        ->find($singleWarehouse->getIdWarehouse())->getName(), 'stock' => $singleWarehouse->getStock()];
                }
            } catch (\Exception $exception) {
                $this->logger->error("An error has ocurred getting some warehouse by product id = " . $product->getId() . ", name = " . $product->getName() . ". Exception: " . $exception);
            }

            try {


                $productDto
                    ->setId($product->getId())
                    ->setName($product->getName())
                    ->setReference($product->getReference())
                    ->setFormat($this->em->getRepository(Format::class)->find($product->getIdFormat())->getName())
                    ->setProductSet($product->getSetProduct())
                    ->setZone($this->em->getRepository(Zone::class)->find($product->getIdZone())->getName())
                    ->setProductUse($this->em->getRepository(ProductUse::class)->find($product->getIdUse())->getName())
                    ->setProductType($this->em->getRepository(ProductType::class)->find($product->getIdType())->getName())
                    ->setDecorated($this->em->getRepository(Decorated::class)->find($product->getIdDecorated())->getName())
                    ->setMaterial($this->em->getRepository(Material::class)->find($product->getIdMaterial())->getName())
                    ->setCurrency($this->em->getRepository(Currency::class)->find($product->getIdCurrency())->getName())
                    ->setPrice($product->getPrice())
                    ->setDiscount($product->getDiscount())
                    ->setStock($arrayWarehouseNameAndStock);

                $dtoArray[] = $productDto;
            } catch (\Exception $exception) {

                $this->logger->error("An error has ocurred retrieving product : id = " . $product->getId() . ", name = " . $product->getName() . ". Exception: " . $exception);
            }
        }
        return $dtoArray;
    }
    /* --------------- End product DTO section ------------------------- */

    public function AssignDataToProductCartListDTO(Product $product, int $quantity): CartProductListDTO
    {
        $productCartListDTO = new CartProductListDTO();
        $productCartListDTO->setName($product->getName())
            ->setImage($product->getImageCover())
            ->setZone($product->getIdZone()->getName())
            ->setFormat($product->getIdFormat()->getName())
            ->setQuantity($quantity)
            ->setReference($product->getReference())
            ->setProductId($product->getId());
        if ($product->getIdType()->getId() == $this->getOutletId()){
            $productCartListDTO->setPrice($product->getPrice());
        } else {
            $productCartListDTO->setPrice(0);
        }

        return $productCartListDTO;
    }

    /* --------------- Images section ------------------------- */
    public function getNewProductCoverPicture(Request $request)
    {
        return $request->files->get('cover');

    }

    public function getNewProductAmbientPicture(Request $request)
    {
        return $request->files->get('ambient');

    }

    public function getNewProductMinPictures(Request $request): array
    {
        $minPictures = $request->files->all();
        $picturesToSave = [];
        foreach ($minPictures as $key => $picture) {
            if (str_starts_with($key, "min")) {
                $picturesToSave[] = $picture;
                // $picturesToSave[] = $picture->guessExtension();
            }
        }

        return $picturesToSave;

    }
    /* --------------- End images section ---------------------- */

    /* --------------- Warehouse section ------------------------- */
    public function getWarehousesAndStocks(Request $request, Product $product): array
    {
        $fields = $request->request->all();
        $i = 0;
        $idWarehouse = 0;
        $warehousesAndStocks = [];
        foreach ($fields as $key => $field) {

            if (str_starts_with($key, "sw") || str_starts_with($key, "ws")) {
                $i++;
                if ($i % 2 == 0) {
                    $stockProduct = new StockProduct();
                    $stockWarehouse = (int)$field;
                    $stockProduct
                        ->setIdProduct($product->getId())
                        ->setIdWarehouse($idWarehouse)
                        ->setStock($stockWarehouse);
                    $warehousesAndStocks [] = $stockProduct;
                } else {
                    $idWarehouse = (int)$field;
                }
            }
        }

        return $warehousesAndStocks;
    }

    public function getWarehousesAndExistingStocksToUpdate(Request $request, Product $product): array
    {
        $fields = $request->request->all();
        $i = 0;
        $warehousesAndStocks = [];
        $idWarehouse = 0;
        $stockProductId = 0;
        foreach ($fields as $key => $field) {

            if (str_starts_with($key, "warehouseStockId") ||
                str_starts_with($key, "sw") ||
                str_starts_with($key, "ws")) {
                $i++;

                if ($i == 1) {
                    $stockProductId = (int)$field;
                }
                if ($i == 2) {
                    $idWarehouse = (int)$field;
                }
                if ($i == 3) {
                    $stockProduct = $this->em->getRepository(StockProduct::class)->find($stockProductId);
                    $stockProductWarehouse = (int)$field;
                    $stockProduct
                        ->setIdProduct($product->getId())
                        ->setIdWarehouse($idWarehouse)
                        ->setStock($stockProductWarehouse);
                    $warehousesAndStocks [] = $stockProduct;
                    $i = 0;
                }
            }
        }

        return $warehousesAndStocks;
    }

    public function GetWarehousesAndStocksToDelete(Request $request)
    {
        $productStockByWarehousetoDeleteArray = [];

        $fields = $request->request->all();

        foreach ($fields as $key => $field) {

            if (str_starts_with($key, "deleteWSid")) {
                try {
                    $productStockByWarehousetoDeleteArray[] = $this->em->
                    getRepository(StockProduct::class)->find((int)$field);
                } catch (\Exception $exception) {
                    $this->logger->error('Has been an error looking for StockProduct register. Exception: ' . $exception);
                }

            }

        }
        return $productStockByWarehousetoDeleteArray;
    }

    public function GetNewStockWarehouseByProduct(Request  $request, Product $product): array
    {
        $fields = $request->request->all();
        $i = 0;
        $idWarehouse = 0;
        $newStockWarehouseByProduct = [];
        foreach ($fields as $key => $field) {

            if (str_starts_with($key, "newSw") || str_starts_with($key, "newWs")) {
                $i++;
                if ($i % 2 == 0) {
                    $stockProduct = new StockProduct();
                    $stockWarehouse = (int)$field;
                    $stockProduct
                        ->setIdProduct($product->getId())
                        ->setIdWarehouse($idWarehouse)
                        ->setStock($stockWarehouse);
                    $newStockWarehouseByProduct [] = $stockProduct;
                } else {
                    $idWarehouse = (int)$field;
                }
            }
        }

        return $newStockWarehouseByProduct;
    }
    /* --------------- End warehouse section ---------------------- */


    /*-----------------   Get Zones, Materials and Formats   ---------------------- */
    /**
     * @return array
     */
    public function getZonesMaterialsAndFormats(): array
    {
        $zones = $this->em->getRepository(Zone::class)->findAll();
        $materials = $this->em->getRepository(Material::class)->findAll();
        $formats = $this->em->getRepository(Format::class)->findAll();
        return array($zones, $materials, $formats);
    }

    /*-----------------   End Zones, Materials and Formats   ---------------------- */

    public function getOutletId(): ?int
    {
        return $this->em->getRepository(ProductType::class)->findOneBy(['name' => 'Outlet'])->getId();
    }


    public
    function __toString()
    {
        return "";
    }

}