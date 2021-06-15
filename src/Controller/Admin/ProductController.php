<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Countries;
//use App\Entity\ProductImageAmbient;
//use App\Entity\ProductImageCover;
use App\Entity\ProductImageMin;
use App\Entity\ProductUse;
use App\Entity\Zone;
use App\Entity\Currency;
use App\Entity\Decorated;
use App\Entity\Format;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\StockProduct;
use App\Entity\Warehouse;
use App\Repository\StockRepository;
use App\Services\GlobalManager;
use App\Services\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProductController
 * @package App\Controller
 * @Route ("/admin/product")
 */
class ProductController extends AbstractController

{

    private ProductManager $productManager;
    private GlobalManager $globalManager;
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;
    private SluggerInterface $slugger;
    private LoggerInterface $logger;

    function __construct(ProductManager $productManager, EntityManagerInterface $em, GlobalManager $globalManager
        , ValidatorInterface $validator, SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->productManager = $productManager;
        $this->em = $em;
        $this->globalManager = $globalManager;
        $this->validator = $validator;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }


    /**
     * @Route("/", name="get_all_products")
     */
    public function GetAllProducts(): Response

    {

        $products = $this->em->getRepository(Product::class)->findAll();
        $productsDTO = $this->productManager->AssingDataToDTOarray($products);
        $zone = "";
        $material = "";
        $format = "";
        $type = "";
        $types = $this->em->getRepository(ProductType::class)->findAll();
        list($zones, $materials, $formats) = $this->productManager->getZonesMaterialsAndFormats();

        return $this->render('admin/product/all.html.twig', [
            'title' => 'Productos',
            'products' => $productsDTO,
            'zones' => $zones,
            'materials' => $materials,
            'formats' => $formats,
            'types' => $types,
            'selectedZone' => $zone,
            'selectedMaterial' => $material,
            'selectedFormat' => $format,
            'selectedType' => $type,
        ]);
    }

    /**
     * @Route ("/products-filtered", name="filter_admin_products", methods={"GET"})
     */
    public function FilterProducts(Request $request)
    {
        $zone = $request->query->get('zone');
        $material = $request->query->get('material');
        $format = $request->query->get('format');
        $type = $request->query->get('type');;

        //dd($request->query->all());

        $zonelength = strlen($zone);
        $materialLength = strlen($material);
        $formatLength = strlen($format);
        $typeLength = strlen($type);
      // dd($zonelength, $materialLength, $formatLength, $typeLength);
        $products = [];

        // Filter by Zone
        if ($zonelength>0 && $materialLength<1 &&  $formatLength<1 &&  $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone]);
        }

        // Filter by Material
        if ($materialLength>0 && $zonelength<1 &&  $formatLength<1 && $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idMaterial' => $material]);
        }

        // Filter by Format
        if ( $formatLength>0 && $zonelength<1 && $materialLength<1 && $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idFormat' => $format]);
        }

        // Filter by Type
        if ($typeLength>0 && $formatLength<1 && $zonelength<1 && $materialLength<1 ){
            $products = $this->em->getRepository(Product::class)->findBy(['idType' => $type]);
        }

        // Filter by Zone and Material
        if ($zonelength>0 && $materialLength>0 &&  $formatLength<1 && $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone, 'idMaterial' => $material]);
        }

        // Filter by Zone and Format
        if ($zonelength>0 && $formatLength>0 && $materialLength<1 && $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone, 'idFormat' => $format]);
        }

        // Filter by Zone and Type
        if ($zonelength>0 && $typeLength>0 && $formatLength<1 && $materialLength<1 ){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone, 'idType' => $type]);
        }

        // Filter by Zone, Material and Type
        if ($materialLength>0 && $typeLength>0  && $zonelength>0 && $formatLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone,'idMaterial' => $material,'idType' => $type]);
        }

        // Filter by Zone, Format and Type
        if ($zonelength>0 && $formatLength>0 &&$typeLength>0 && $materialLength<1  ){
            $products = $this->em->getRepository(Product::class)->findBy(['idZone' => $zone,'idFormat' => $format,'idType' => $type]);
        }


        // Filter by Material and Format
        if ($materialLength>0 && $formatLength>0 && $zonelength<1 && $typeLength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idMaterial' => $material, 'idFormat' => $format]);
        }

        // Filter by Material and Type
        if ($materialLength>0 && $typeLength>0 && $formatLength<1 && $zonelength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idMaterial' => $material, 'idType' => $type]);
        }

        // Filter by Material, Format and Type
        if ($materialLength>0 && $typeLength>0 && $formatLength>0 && $zonelength<1){
            $products = $this->em->getRepository(Product::class)->findBy(['idMaterial' => $material,'idFormat' => $format, 'idType' => $type]);
        }


        // Filter by Format and Type
        if ($materialLength<1 && $typeLength>0 && $formatLength>0 && $zonelength<1){
            $products = $this->em->getRepository(Product::class)->findBy([ 'idFormat' => $format, 'idType' => $type]);
        }


        // Filter by Zone, Material, Format and Type
        if ($zonelength>0 && $materialLength>0 && $formatLength>0 && $typeLength>0){
            $products = $this->em->getRepository(Product::class)
                ->findBy(['idZone' => $zone,'idMaterial' => $material, 'idFormat' => $format, 'idType' => $type]);
        }

        //Filter by All
        if ($zonelength<1 && $materialLength<1 &&  $formatLength<1 && $typeLength<1){
            return $this->redirectToRoute('get_all_products');
        }

        $productsDTO = $this->productManager->AssingDataToDTOarray($products);
        list($zones, $materials, $formats) = $this->productManager->getZonesMaterialsAndFormats();
        $types = $this->em->getRepository(ProductType::class)->findAll();
        return $this->render('admin/product/all.html.twig', [
            'title' => 'Productos',
            'products' => $productsDTO,
            'zones' => $zones,
            'materials' => $materials,
            'formats' => $formats,
            'types' => $types,
            'selectedZone' => $zone,
            'selectedMaterial' => $material,
            'selectedFormat' => $format,
            'selectedType' => $type,
        ]);
    }

    /**
     * @Route("/create", name="create_product_get", methods={"GET"})
     */
    public function CreateProductGet(): Response
    {
        $product = new Product();
        $zones = $this->em->getRepository(Zone::class)->findAll();
        $warehouses = $this->em->getRepository(Warehouse::class)->findAll();
        $productTypes = $this->em->getRepository(ProductType::class)->findAll();
        $decorated = $this->em->getRepository(Decorated::class)->findAll();
        $materials = $this->em->getRepository(Material::class)->findAll();
        $warehousesByProductId = $this->em->getRepository(StockProduct::class)->findWarehousesByProductId($product->getName());
        $formats = $this->em->getRepository(Format::class)->findAll();
        $currencies = $this->em->getRepository(Currency::class)->findAll();
        $uses = $this->em->getRepository(ProductUse::class)->findAll();
        $countries = $this->em->getRepository(Countries::class)->findAll();
        $brands = $this->em->getRepository(Brand::class)->findAll();
        return $this->render('admin/product/add.html.twig', [
            'title' => 'Crear producto',
            'product' => $product,
            'zones' => $zones,
            'warehouses' => $warehouses,
            'productTypes' => $productTypes,
            'decorated' => $decorated,
            'materials' => $materials,
            'warehousesByProduct' => $warehousesByProductId,
            'formats' => $formats,
            'currencies' => $currencies,
            'uses' => $uses,
            'countries' => $countries,
            'brands' => $brands,
        ]);
    }

    /**
     * @Route("/create", name="create_product_post", methods={"post"})
     */
    public function CreateProductPost(Request $request, SluggerInterface $slugger): Response
    {
       // dd($request->request->all());
        $product = $this->productManager->AssingDataToNewProduct($request);

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($product))) {
            return $this->redirectToRoute('get_all_products');
        }

        try {
            $this->em->persist($product);
            $this->em->flush();
            //return  $this->redirectToRoute('get_all_products');
            $this->addFlash('success', "Producto " . $product->getName() . " creado.");
        } catch (\Exception $exception) {
            $this->addFlash('success', "El producto " . $product->getName() . " no se pudo crear.");
            return $this->redirectToRoute('get_all_products');
        }

        $warehousesWithStock = $this->productManager->getWarehousesAndStocks($request, $product);
        //dd($warehousesWithStock);
        $errors = [];
        foreach ($warehousesWithStock as $item) {
            if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($item))) {
                $errors[] = true;
                try {
                    $this->em->remove($product);
                    $this->em->flush();
                    $this->addFlash('fail', "ha habido un problema asignando el stock del producto. Producto eliminado. Vuelva a registrarlo.");
                } catch (\Exception $exception) {
                    $this->addFlash('fail', "ha habido un problema asignando el stock del producto. Vuelva a intentarlo");
                }

                return $this->redirectToRoute('get_all_products');
            }
        }

        if (in_array(true, $errors)) {
            return $this->redirectToRoute('get_all_products');
        }

        foreach ($warehousesWithStock as $singleStockProduct) {
            try {
                $this->em->persist($singleStockProduct);
                $this->em->flush();
            } catch (\Exception $exception) {
                $this->addFlash('fail', 'Ha habido un error al persistir el stock del producto.');
            }
        }

        $coverImage = $this->productManager->getNewProductCoverPicture($request);
        $ambientImage = $this->productManager->getNewProductAmbientPicture($request);
        $minImages = $this->productManager->getNewProductMinPictures($request);

        // dd($this->saveCoverImage($product, $coverImage));
        if ($coverImage) {
            if ($uploadedCoverImage = $this->saveCoverImage($product, $coverImage)) {
                try {
                    $this->em->persist($uploadedCoverImage);
                    $this->em->flush();
                } catch (FileException $e) {
                    $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen de portada");

                }
            }
        }

        if ($ambientImage) {
            if ($uploadedAmbientImage = $this->saveAmbientImage($product, $ambientImage)) {
                try {
                    $this->em->persist($uploadedAmbientImage);
                    $this->em->flush();
                } catch (FileException $e) {
                    $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen de ambiente");

                }
            }
        }

        if (count($minImages) > 0) {
            $tinyImages = $this->saveMinImages($product, $minImages);
            if (count($tinyImages) > 0) {
                foreach ($tinyImages as $tinyImage) {
                    try {
                        $this->em->persist($tinyImage);
                        $this->em->flush();
                    } catch (FileException $e) {
                        $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen " . $tinyImage->getClientOriginalName());

                    }
                }
            }
        }

        return $this->redirectToRoute("get_all_products");

    }

    /**
     * @Route("/show/{id<\d+>}", name="show_product", methods={"GET"})
     */
    public function ShowProduct(int $id, Request $request)
    {
        if ($product = $this->em->getRepository(Product::class)->find($id)) {
            if (count($this->validator->validate($product)) > 0) {
                $this->addFlash('fail', 'Ha ocurrido un error transformando el producto a DTO');
                return $this->redirectToRoute('get_all_products');
            }

            if ($productDto = $this->productManager->AssignDataToProductShow($product)) {

                return $this->render('admin/product/show.html.twig', [
                    'title' => 'Producto',
                    'product' => $productDto,

                ]);
            }
            $this->addFlash('fail', "No se pudo convertir el producto a DTO");
            return $this->redirectToRoute("get_all_products");

        }
        $this->addFlash('fail', "Producto " . $id . " no encontrado");
        return $this->redirectToRoute("get_all_products");
    }

    /**
     * @Route("/edit/{id<\d+>}", name="edit_product_get", methods={"GET"})
     */
    public function EditProductGet(int $id, Request $request)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        //dd($product);
        if (!$product) {
            // $arrayWarehouseNameAndStock = [];
            $this->addFlash('fail', "Producto " . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_products');
        }


        $zones = $this->em->getRepository(Zone::class)->findAll();
        $warehouses = $this->em->getRepository(Warehouse::class)->findAll();
        $productTypes = $this->em->getRepository(ProductType::class)->findAll();
        $decorated = $this->em->getRepository(Decorated::class)->findAll();
        $materials = $this->em->getRepository(Material::class)->findAll();
        $warehousesByProductId = $this->em->getRepository(StockProduct::class)->findWarehousesByProductId($product->getId());
        $formats = $this->em->getRepository(Format::class)->findAll();
        $currencies = $this->em->getRepository(Currency::class)->find($product->getIdCurrency());
        $uses = $this->em->getRepository(ProductUse::class)->findAll();
       // $imageAmbientUrl = $this->em->getRepository(ProductImageAmbient::class)->findBy(['idProduct' => $product->getId()]);
        //$imageCoverUrl = $this->em->getRepository(ProductImageCover::class)->findBy(['idProduct' => $product->getId()]);
        $minImagesUrl = $this->em->getRepository(ProductImageMin::class)->findBy(['idProduct' => $product->getId()]);
        $countries = $this->em->getRepository(Countries::class)->findAll();
        $brands = $this->em->getRepository(Brand::class)->findAll();

        return $this->render('admin/product/edit.html.twig', [
            'title' => 'Editar producto',
            'product' => $product,
            'zones' => $zones,
            'warehouses' => $warehouses,
            'productTypes' => $productTypes,
            'decorated' => $decorated,
            'materials' => $materials,
            'warehousesByProduct' => $warehousesByProductId,
            'formats' => $formats,
            'currencies' => $currencies,
            'uses' => $uses,
           // 'imageAmbientUrl' => $imageAmbientUrl,
            //'imageCoverUrl' => $imageCoverUrl,
            'minImagesUrl' => $minImagesUrl,
            'countries' => $countries,
            'brands' => $brands,
        ]);

    }

    /**
     * @Route("/edit/{id<\d+>}", name="edit_product_put", methods={"PUT"})
     */
    public function EditProductPut(Request $request, int $id)
    {
       // dd($request->request->all());
        if ($id != (int)$request->request->get('idProduct')) {
            $this->addFlash('fail', 'El producto a editar no se corresponde con el indicado en el formulario. id: ' . $id);
            return $this->redirectToRoute('get_all_products');
        }
        $product = $this->productManager->GetExistingProductAndAssignData($request, $id);
        //dd($product);
        if (!$product) {
            $this->addFlash('fail', "Producto " . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_products');
        }

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($product))) {
            return $this->redirectToRoute('get_all_products');
        }
        $StockWarehousesToUpdate = $this->productManager->getWarehousesAndExistingStocksToUpdate($request, $product);

        //dd($StockWarehousesToUpdate);

        if (count($StockWarehousesToUpdate) > 0) {
            foreach ($StockWarehousesToUpdate as $item) {
                if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($item))) {
                    return $this->redirectToRoute('get_all_products');
                }
            }
        }
        $newStockWarehouseToUpdate = $this->productManager->GetNewStockWarehouseByProduct($request, $product);
        //dd($newStockWarehouseToUpdate);
        if (count($newStockWarehouseToUpdate) > 0) {
            foreach ($newStockWarehouseToUpdate as $item) {
                if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($item))) {
                    return $this->redirectToRoute('get_all_products');
                }
            }
        }

        $stockWarehouseToDelete = $this->productManager->GetWarehousesAndStocksToDelete($request);
       // dd($this->productManager->GetWarehousesAndStocksToDelete($request));

        $this->em->persist($product);
        if (count($newStockWarehouseToUpdate) > 0) {
            foreach ($StockWarehousesToUpdate as $item) {
                $this->em->persist($item);
            }
        }
        if (count($newStockWarehouseToUpdate) > 0) {
            foreach ($newStockWarehouseToUpdate as $item) {
                $this->em->persist($item);
            }
        }

        if (count($stockWarehouseToDelete) > 0){
            foreach ($stockWarehouseToDelete as $item) {
                $this->em->remove($item);
            }
        }
        $this->em->flush();
        $this->addFlash('success', 'Producto Editado');

        return $this->redirectToRoute('show_product', ['id' => $id]);


    }

    /**
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @Route("/delete/{id<\d+>}", name="delete_product", methods={"DELETE"})
     */
    public function DeleteProduct(Request $request, int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product){
            $this->addFlash('fail','Producto #' . $id . ' no encontrado');
        }
        $coverImageByProduct = $product->getImageCover();
        $ambientImageByProduct = $product->getImageAmbient();
        $minImagenesByProduct = $this->em->getRepository(ProductImageMin::class)->findBy(['idProduct' => $product->getId()]);
       // dd($product, $coverImagesByProduct, $ambientImagesByProduct, $minImagenesByProduct);

            if (!$this->CheckCsrfToken('delete' . $id, $request)) {
                $this->addFlash('fail','Token CSRF no valido.');
                return  $this->redirectToRoute('get_all_products');
            }

            try {
                $fs = new Filesystem();
               // dd(count($coverImagesByProduct),count($ambientImagesByProduct),count($minImagenesByProduct));
                $this->em->remove($product);
                if ($coverImageByProduct != null) {
                    $fs->remove($this->getParameter('directory_product_images').'/'. $coverImageByProduct );
                }
                if ($ambientImageByProduct != null) {

                        $fs->remove($this->getParameter('directory_product_images').'/'. $ambientImageByProduct );
                }


                foreach ($minImagenesByProduct as $minImage) {
                    $fs->remove($this->getParameter('directory_product_images').'/'. $minImage->getImageUrl() );
                }

                $this->em->flush();
                $this->addFlash('success', 'Producto ' . $product->getName() . ' eliminado.');

                return $this->redirectToRoute('get_all_products');
           } catch (\Exception $exception) {

                $this->addFlash('fail', 'Ha habido un error al intentar eliminar el producto '
                    . $product->getName() . '. Intentelo mas tarde');
                $this->logger->error("Has been an Exception deleting product id #" . $product->getId() . ". Exception: " . $exception);
                return $this->redirectToRoute('get_all_products');
            }




    }

    /**
     * @Route("/{idProduct<\d+>}/delete/picture/cover/{idCoverImage<\d+>}", name="delete_cover_image", methods={"DELETE"})
     */
    public function DeleteProductCoverImage(int $idProduct, int $idCoverImage, Request $request)
    {
        if ($product = $this->em->getRepository(Product::class)->find($idProduct)){
            $this->DeleteImageOnServer($product->getImageCover());
            $product->setImageCover(null);
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Imagen de portada eliminada');
        }

        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }

    /**
     * @Route("/{idProduct<\d+>}/delete/ambient-picture", name="delete_ambient_image", methods={"DELETE"})
     */
    public function DeleteProductAmbientImage(Request $request, int $idProduct)
    {
        try {

        if ($product = $this->em->getRepository(Product::class)->find($idProduct)){
            $this->DeleteImageOnServer($product->getImageAmbient());
            $product->setImageAmbient(null);
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Imagen de ambiente eliminada');
        }  else {
            $this->addFlash('fail','Producto'. $idProduct.' no encontrado');
        }
        }catch (\Exception $e){
        $this->addFlash('fail', 'Ha ocurrido un error eliminando la imagen ambiente');
    }
        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }

    /**
     * @Route("/{idProduct<\d+>}/delete/picture/cover/{idMinmage<\d+>}/", name="delete_min_image", methods={"DELETE"})
     */
    public function DeleteProductMinImage(Request $request, int $idProduct, int $idMinmage)
    {
        //dd($this->HandlerDeleteImage(ProductImageMin::class, $idProduct, $idMinmage, $request));
        if ($this->HandlerDeleteImage(ProductImageMin::class, $idProduct, $idMinmage, $request)) {
            $this->addFlash('success', 'Imagen de miniatura eliminada');
            return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
        }
        $this->addFlash('fail', 'No se pudo eliminar la imagen de portada numero ' . $idMinmage);
        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }

    /**
     * @Route("/{idProduct<\d+>}/save-picture/cover", name="save_single_cover_image", methods={"POST"})
     */
    public function SaveSingleCoverImage(int $idProduct, Request $request )
    {
        //dd($this->productManager->getNewProductCoverPicture($request));

        if ($product = $this->em->getRepository(Product::class)->find($idProduct)) {

            if ($coverImageToPersist = $this->
            saveCoverImage($product, $this->productManager->getNewProductCoverPicture($request))) {
                try {
                    $this->em->persist($coverImageToPersist);
                    $this->em->flush();
                    $this->addFlash('success', 'Imagen de portada agregada.');

                } catch (\Exception $e) {
                    $this->addFlash($this->getParameter('fail_message'),
                        "No se ha podido subir la imagen " . $this->productManager->getNewProductCoverPicture($request)->getClientOriginalName());
                    $this->logger->error("Has been an storing into database the cover image url. Exception: " . $e);

                }
                return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
            }
            $this->addFlash('fail', 'No se pudo obtener la imager para guardar.');
            return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
        }
        $this->addFlash('fail', 'No se pudo obtener el producto al que se le asigna la imagen.');
        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }

    /**
     * @Route("/{idProduct<\d+>}/save-picture/ambient", name="save_single_ambient_image", methods={"POST"})
     */
    public function SaveSingleAmbientImage(int $idProduct, Request $request)
    {
        //dd($this->productManager->getNewProductAmbientPicture($request));
        if ($product = $this->em->getRepository(Product::class)->find($idProduct)) {
            if ($ambientImageToPersist = $this->
            saveAmbientImage($product, $this->productManager->getNewProductAmbientPicture($request))) {
                try {
                    $this->em->persist($ambientImageToPersist);
                    $this->em->flush();
                    $this->addFlash('success', 'Imagen de ambiente agregada.');

                } catch (\Exception $e) {
                    $this->addFlash($this->getParameter('fail_message'),
                        "No se ha podido subir la imagen " . $this->productManager->getNewProductAmbientPicture($request)->getClientOriginalName());
                    $this->logger->error("Has been an error storing into database the ambient image url. Exception: " . $e);

                }
                return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
            }
            $this->addFlash('fail', 'No se pudo obtener la imager para guardar.');
            return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
        }
        $this->addFlash('fail', 'No se pudo obtener el producto al que se le asigna la imagen.');
        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }

    /**
     * @Route("/{idProduct<\d+>}/save-picture/min", name="save_single_min_image", methods={"POST"})
     */
    public function SaveSingleMinImage(Request $request, int $idProduct)
    {
        if ($product = $this->em->getRepository(Product::class)->find($idProduct)) {
            $minImageFromRequest = $request->files->get('min');
            if ($minImageToPersist = $this->saveSingleMinImageOnServer($product, $minImageFromRequest)) {
                try {
                    $this->em->persist($minImageToPersist);
                    $this->em->flush();
                    $this->addFlash('success', 'Imagen miniatura agregada.');

                } catch (\Exception $e) {
                    $this->addFlash($this->getParameter('fail_message'),
                        "No se ha podido subir la imagen " . $request->files->get('min')->getClientOriginalName());
                    $this->logger->error("Has been an storing into database the cover image url. Exception: " . $e);

                }
                return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
            }
            $this->addFlash('fail', 'No se pudo obtener la imager para guardar.');
            return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
        }
        $this->addFlash('fail', 'No se pudo obtener el producto al que se le asigna la imagen.');
        return $this->redirectToRoute('edit_product_get', ['id' => $idProduct]);
    }



    public function CheckCsrfToken(string $idCsrf, Request $request): bool
    {

        $this->isCsrfTokenValid($idCsrf, $request->request->get('_token')) ? $validCsrf = true : $validCsrf = false;

        return $validCsrf;
    }

    public function saveAmbientImage(Product $product, $ambientImage): ?Product
    {

        if ($ambientImage) {
           // $ambientImageToPersist = new ProductImageAmbient();
            $originalFilename = pathinfo($ambientImage->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $ambientImage->guessExtension();
            try {
                $ambientImage->move(
                    $this->getParameter('directory_product_images'),
                    $newFilename
                );
               // $ambientImageToPersist->setIdProduct($product->getId())->setImageUrl($newFilename);
                $product->setImageAmbient($newFilename);
                return $product;
            } catch (FileException $e) {
                $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen " . $ambientImage->getClientOriginalName());
                $this->logger->error("Has been an file error storing an ambient image into server. Exception: " . $e);
                return null;
            } catch (\Exception $exception) {
                $this->logger->error("Has been an error storing ab ambient image into server. Exception: " . $exception);
                return null;
            }
        }
        return null;
    }

    public function saveCoverImage(Product $product, $coverImage): ?Product
    {

        if ($coverImage) {
            //$coverImageToPersist = new ProductImageCover();
            //dd($coverImage->guessExtension());
            $originalFilename = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverImage->guessExtension();
            try {
                $coverImage->move(
                    $this->getParameter('directory_product_images'),
                    $newFilename
                );
                //$coverImageToPersist->setIdProduct($product->getId())->setImageUrl($newFilename);
                $product->setImageCover($newFilename);
                return $product;
            } catch (FileException $e) {
                $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen " . $coverImage->getClientOriginalName());
                $this->logger->error("Has been an file error storing a cover image into server. Exception: " . $e);
                return null;
            } catch (\Exception $exception) {
                $this->logger->error("Has been an error storing a cover image into server. Exception: " . $exception);
                return null;
            }
        }
        return null;
    }

    public function saveMinImages(Product $product, array $images): array
    {
        $ProductMinImagesToPersist = [];
        if (count($images) > 0) {
            foreach ($images as $image) {
                if ($image) {
                    $imageToPersist = new ProductImageMin();
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                    try {
                        $image->move(
                            $this->getParameter('directory_product_images'),
                            $newFilename
                        );
                        $imageToPersist
                            ->setIdProduct($product->getId())
                            ->setImageUrl($newFilename);
                        $ProductMinImagesToPersist[] = $imageToPersist;

                    } catch (FileException $e) {
                        $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen " . $image->getClientOriginalName());
                        $this->logger->error("Has been an file error storing a min image into server. Exception: " . $e);

                    } catch (\Exception $exception) {
                        $this->logger->error("Has been an error storing a min image into server. Exception: " . $exception);

                    }
                }

            }
        }
        return $ProductMinImagesToPersist;

    }

    public function saveSingleMinImageOnServer(Product $product, $minImage): ?ProductImageMin
    {
        if ($minImage) {
            $coverImageToPersist = new ProductImageMin();
            $originalFilename = pathinfo($minImage->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $minImage->guessExtension();
            try {
                $minImage->move(
                    $this->getParameter('directory_product_images'),
                    $newFilename
                );
                $coverImageToPersist->setIdProduct($product->getId())->setImageUrl($newFilename);
                return $coverImageToPersist;
            } catch (FileException $e) {
                $this->addFlash($this->getParameter('fail_message'), "No se ha podido subir la imagen " . $minImage->getClientOriginalName());
                $this->logger->error("Has been an file exception storing a min image into server. Exception: " . $e);
                return null;
            } catch (\Exception $exception) {
                $this->logger->error("Has been an exception storing a min image into server. Exception: " . $exception);
                return null;
            }
        }
        return null;
    }

    public function DeleteImageOnServer(string $imageName): bool
    {
     try {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->getParameter('directory_product_images') . "/" . $imageName);
          $this->addFlash("success", "Image " . $imageName . " eliminada.");
            return true;
       } catch (\Exception $exception) {
            $this->addFlash('fail', 'Ha ocurrido un error eliminando la imagen ' . $imageName . 'en el servidor.');
            $this->logger->error("Has been an error deleting image #" . $imageName . ". Exception: " . $exception);
            return false;
        }

    }

    public function HandlerDeleteImage(string $className, int $idProduct, int $idImage, Request $request): bool
    {
        try {
            $product = $this->em->getRepository(Product::class)->find($idProduct);
            if ($product) {

                if ($image = $this->em->getRepository($className)->find($idImage)) {
                    //dd($this->CheckCsrfToken("delete" . $product->getId(), $request));
                    if (!$this->CheckCsrfToken("delete" . $product->getId(), $request)) {
                        //dd('entra en token');
                        $this->addFlash('Fail', 'Token CSRF No valido');
                        return false;
                    }
                        //dd($this->DeleteImageOnServer($image->getImageUrl()));
                    if (!$this->DeleteImageOnServer($image->getImageUrl())) {
                        return false;
                    };
                    $this->em->remove($image);
                    $this->em->flush();
                    return true;
                } else {
                    $this->addFlash("fail", "Imagen" . $image->getId() . " no encontrada");
                    return false;
                }
            }
            $this->addFlash('fail', "Producto " . $idProduct . " no encontrado.");
            return false;
        } catch (\Exception $exception) {
            $this->addFlash('fail', 'Ha ocurrido un error eliminando la imagen intentalo mas tarde.');
            $this->logger->error("Has been an error deleting image #" . $idImage . ". Exception: " . $exception);
            return false;

        }


    }



}
