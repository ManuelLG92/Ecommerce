<?php

namespace App\Controller\Admin;


use App\Entity\City;
use App\Entity\ClientDiscount;
use App\Entity\Countries;
use App\Entity\CustomUser;
use App\Entity\ProductType;
use App\Entity\Regions;
use App\Services\GlobalManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route("/admin/user")
 */
class CustomUserController extends AbstractController
{
    private $em;
    private $validator;
    private $globalManager;
    function __construct(EntityManagerInterface  $entityManager, ValidatorInterface $validator, GlobalManager  $globalManager){
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->globalManager = $globalManager;
    }
    /**
     * @Route("/all", name="get_all_clients", methods={"GET"})
     */
    public function index(): Response
    {
        $clients = $this->em->getRepository(CustomUser::class)->findAll();
        return $this->render('admin/clients/index.html.twig', [
            'title' => "Clientes",
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("/create", name="create_client_get", methods={"GET"})
     */
    public function CreateClientGet(): Response
    {

        $client = new CustomUser();
        $countries = $this->em->getRepository(Countries::class)->findAll();
        return $this->render('admin/clients/add.html.twig', [
            'title' => "Crear cliente",
            'client' =>$client,
            'countries' => $countries,
        ]);
    }
    /**
     * @Route("/regions/{id<\d+>}", name="get_regions_json", methods={"GET"})
     */
    public function getRegions(int $id): Response
    {
        $regions = $this->em->getRepository(Regions::class)->findByCountryId($id);

       return  new JsonResponse($regions);
    }

    /**
     * @Route("/cities/{id<\d+>}", name="get_cities_json", methods={"GET"})
     */
    public function getCities(int $id): Response
    {
        //dd($id);
       // $region = $this->em->getRepository(Regions::class)->findOneBy(['code'=>$id]);
        $region = $this->em->getRepository(Regions::class)->find($id);
        $cities = $this->em->getRepository(City::class)->findByRegionId($region->getId());
        //dd($cities);

        return  new JsonResponse($cities);
    }
    /**
     * @Route("/create", name="create_client_post", methods={"POST"})
     */
    public function CreateClientPost(Request $request): Response
    {
       // dd($request->request->all());
        $newClient = $this->AssignDataToNewClient($request);
       // dd($newClient);
       //dd($this->validator->validate($newClient));
        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($newClient))){
            return $this->redirectToRoute('create_client_get');
        }

        $this->em->persist($newClient);
        $this->em->flush();

        return  $this->redirectToRoute('get_all_clients');

    }

    /**
     * @Route("/edit/{id<\d+>}", name="edit_client_get", methods={"GET"})
     */
    public function EditClientGet(int $id,Request $request): Response
    {

        if ($client = $this->em->getRepository(CustomUser::class)->find($id)){
            $countries = $this->em->getRepository(Countries::class)->findAll();
            $regions = '';
            $cities = '';
            $clientRegionId = 0;
            $clientCityId = 0;
            if ($client->getCountry()->getId() == $this->em->
                getRepository(Countries::class)->findOneBy(['countryCode'=> 'CO'])->getId()){
               $regions = $this->em->getRepository(Regions::class)->findByCountryId($client->getCountry()->getId());
               // dd($client->getRegion());
               $cities = $this->em->getRepository(City::class)->findByRegionId($client->getRegion());
               // dd($cities);

/*                $clientRegionId = intval($client->getRegion());
                $clientCityId = intval($client->getCity());*/
            }
//            dd($clientRegionId,$clientCityId );
            return $this->render('admin/clients/edit.html.twig', [
                'title' => "Editar cliente",
                'client' =>$client,
                'countries'=>$countries,
                'regions' => $regions,
                'cities' => $cities,
    /*            'clienteRegionId' => $clientRegionId,
                'clientCityId' => $clientCityId,*/
            ]);
        }
        $this->addFlash('fail','Cliente #' . $id . ' no encontrado');
        return  $this->redirectToRoute('get_all_clients');

    }

    /**
     * @Route("/edit/{id<\d+>}", name="edit_client_put", methods={"PUT"})
     */
    public function EditClientPut(int $id, Request $request): Response
    {
        //dd($request->request->all());
        if ($id != (int)$request->request->get('idClient')) {
            $this->addFlash('fail', 'El usuario a editar no se corresponde con el indicado en el formulario. id: ' . $id);
            return  $this->redirectToRoute('get_all_clients');
        }

        if (!$client = $this->em->getRepository(CustomUser::class)->find($id)){
            $this->addFlash('fail','Cliente #' . $id . ' no encontrado');
            return  $this->redirectToRoute('get_all_clients');
        }

        $client = $this->UpdateDataClient($client, $request);
        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($client))){
            return $this->redirectToRoute('create_client_get');
        }

        $this->em->persist($client);
        $this->em->flush();
        $this->addFlash('success','Cliente '. $client->getCompanyName() . ' editado.');
        return  $this->redirectToRoute('get_all_clients');

    }

    /**
     * @Route("/show/{id<\d+>}", name="show_client", methods={"GET"})
     */
    public function ShowClient(int $id)
    {
        if ($client = $this->em->getRepository(CustomUser::class)->find($id)){
            $region = '';
            $city = '';
           // dd(strcmp($client->getCountry()->getCountryCode(),'CO'));
            if (strcmp($client->getCountry()->getCountryCode(),'CO') == 0){
                    $region = $this->em->getRepository(Regions::class)->find($client->getRegion());
                    $city = $this->em->getRepository(City::class)->find($client->getCity());
            }

            return $this->render('admin/clients/show.html.twig', [
                'title' => $client->getCompanyName(),
                'client' =>$client,
                'region' => $region,
                'city' => $city,
            ]);
        }
        $this->addFlash('fail','Cliente #' . $id . ' no encontrado');
        return  $this->redirectToRoute('get_all_clients');
    }

    /**
     * @Route("/{id<\d+>}/discount", name="show_discount_user", methods={"GET"})
     */
    public function ShowUserDiscounts(int $id)
    {
        $client = $this->em->getRepository(CustomUser::class)->find($id);
        if (!$client){
            $this->addFlash('fail','Cliente #' . $id . " no encontrado.");
            return $this->redirectToRoute('get_all_clients');
        }
        $discountByUser= $this->em->getRepository(ClientDiscount::class)->findBy(['CustomUser'=> $client] );
        $productTypes = $this->em->getRepository(ProductType::class)->findAll();
        $discountsId = [];
        $productTypesId = [];

        foreach ( $productTypes as $itemProductType) {
           $productTypesId[] = $itemProductType->getId();
        }
        foreach ($discountByUser as $itemDiscount) {
            $discountsId[] = $itemDiscount->getProductTypeDiscount()->getId();
        }

        $noDiscounts = array_diff($productTypesId, $discountsId);


        return $this->render('admin/clients/discounts.html.twig', [
            'title' => "Descuentos del cliente",
            'client' => $client,
            'productTypes' => $productTypes,
            'discounts' =>$discountByUser,
            'no_discounts' => $noDiscounts,

        ]);
    }

    /**
     * @Route("/{id<\d+>}/discount", name="create_user_discount", methods={"POST"})
     */
    public function CreateCustomUserDiscount(int $id, Request $request)
    {
        //dd($request->request->all());
        if (!$customUser = $this->em->getRepository(CustomUser::class)->find($id)){
            $this->addFlash('fail','Cliente #' . $id . ' no encontrado.');
            $this->redirectToRoute('get_all_clients');
        }
        if ($id != $request->request->get('clientId')){
            $this->addFlash('fail','El usuario de la url no coincide con el del formulario');
            $this->redirectToRoute('get_all_clients');
        }
        if (!$newUserDiscount = $this->AssingDataToNewClientDiscount($customUser,$request)){
           return $this->redirectToRoute('show_discount_user', ['id' => $id]);
        }

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($newUserDiscount))){
            return $this->redirectToRoute('show_discount_user', ['id' => $id]);
        }
        try {
            $this->em->persist($newUserDiscount);
            $this->em->flush();
            $this->addFlash('success','Descuento creado exitosamente');
        } catch (\Exception $exception){
            $this->addFlash('fail', 'Ha ocurrido un error almacenando el nuevo descuento');
        }

        return $this->redirectToRoute('show_discount_user', ['id' => $id]);
        }

    /**
     * @Route("/{id<\d+>}/discount/{idDiscount<\d+>}", name="edit_user_discount_put", methods={"PUT"})
     */
    public function EditCustomUserDiscount(int $id, int $idDiscount, Request $request)
    {
       // dd($request->request->all());
        if (!$customUser = $this->em->getRepository(CustomUser::class)->find($id)){
            $this->addFlash('fail','Cliente #' . $id . ' no encontrado.');
            $this->redirectToRoute('get_all_clients');
        }
        if ($id != $request->request->get('clientId')){
            $this->addFlash('fail','El usuario de la url no coincide con el del formulario');
            $this->redirectToRoute('get_all_clients');
        }
        if (!$clientDiscount = $this->em->getRepository(ClientDiscount::class)->find($idDiscount)){
            $this->addFlash('fail','El descuento ' . $idDiscount . ' no encontrado.');
            $this->redirectToRoute('get_all_clients');
        }
        if (!$updateUserDiscount = $this->UpdateDataClientDiscount($clientDiscount, $customUser,$request)){
            return $this->redirectToRoute('show_discount_user', ['id' => $id]);
        }

        if ($this->globalManager->CheckErrorsOnValidation($this->validator->validate($updateUserDiscount))){
            return $this->redirectToRoute('show_discount_user', ['id' => $id]);
        }
        try {
            $this->em->persist($updateUserDiscount);
            $this->em->flush();
            $this->addFlash('success','Descuento editado exitosamente');
        } catch (\Exception $exception){
            $this->addFlash('fail', 'Ha ocurrido un error almacenando el nuevo descuento');
        }

        return $this->redirectToRoute('show_discount_user', ['id' => $id]);
    }

    /**
     * @Route("/{id<\d+>}/discount/delete/{idDiscount<\d+>}", name="delete_user_discount_put", methods={"DELETE"})
     */
    public function DeleteCustomUserDiscount(int $id, int $idDiscount, Request $request)
    {
        // dd($request->request->all());
        if (!$customUser = $this->em->getRepository(CustomUser::class)->find($id)){
            $this->addFlash('fail','Cliente #' . $id . ' no encontrado.');
            $this->redirectToRoute('get_all_clients');
        }
        if ($id != $request->request->get('clientId')){
            $this->addFlash('fail','El usuario de la url no coincide con el del formulario');
            $this->redirectToRoute('get_all_clients');
        }
        if (!$clientDiscount = $this->em->getRepository(ClientDiscount::class)->find($idDiscount)){
            $this->addFlash('fail','Descuento #' . $idDiscount . ' no encontrado.');
            $this->redirectToRoute('get_all_clients');
        }

        try {
            $this->em->remove($clientDiscount);
            $this->em->flush();
            $this->addFlash('success','Descuento eliminado exitosamente');
        } catch (\Exception $exception){
            $this->addFlash('fail', 'Ha ocurrido un error eliminando descuento');
        }

        return $this->redirectToRoute('show_discount_user', ['id' => $id]);
    }



    /**
     * @Route("/delete/{id<\d+>}", name="delete_client", methods={"DELETE"})
     */
    public function DeleteClient(int $id, Request $request)
    {
        if (!$client = $this->em->getRepository(CustomUser::class)->find($id)){
            $this->addFlash('fail','Cliente #' . $id . ' no encontrado');
            return  $this->redirectToRoute('get_all_clients');
        }
        if (!$this->isCsrfTokenValid('delete' . $id,$request->request->get('_token'))){
            $this->addFlash('fail','Token CSRF no valido.');
            return  $this->redirectToRoute('get_all_clients');
        }

        $this->em->remove($client);
        $this->em->flush();
        $this->addFlash('success','Cliente ' . $client->getCompanyName() . ' eliminado.');
        return  $this->redirectToRoute('get_all_clients');

    }

    public function AssignDataToCustomUserEntity(CustomUser $customUser, Request $request): CustomUser
    {
        $customUser->setName(trim($request->request->get('name')))
            ->setSurname((trim($request->request->get('surname'))))
            ->setCompanyName((trim($request->request->get('companyName'))))
            ->setNIF((trim($request->request->get('nif'))))
            ->setCountry($this->em->getRepository(Countries::class)->find((trim($request->request->get('country')))))
            ->setCity(trim($request->request->get('city')))
            ->setRegion(trim($request->request->get('region')))
            ->setAddress((trim($request->request->get('address'))))
            ->setPostalCode((trim($request->request->get('postalCode'))))
            ->setPhone((trim($request->request->get('phone'))))
            ->setEmail((trim($request->request->get('email'))))
            ->setAdditionalInformation((trim($request->request->get('aditionalInformation'))));

        return $customUser;
    }

    public function AssignDataToCustomUserEntityUnknownCountry(CustomUser $customUser, Request $request): ?CustomUser
    {
        if (intval(trim($request->request->get('country'))) != 1){
        $customUser->setName(trim($request->request->get('name')))
            ->setSurname((trim($request->request->get('surname'))))
            ->setCompanyName((trim($request->request->get('companyName'))))
            ->setNIF((trim($request->request->get('nif'))))
            ->setCountry($this->em->getRepository(Countries::class)->find((trim($request->request->get('country')))))
            ->setCity(trim($request->request->get('cityUnknown')))
            ->setRegion(trim($request->request->get('regionUnknown')))
            ->setAddress((trim($request->request->get('address'))))
            ->setPostalCode((trim($request->request->get('postalCode'))))
            ->setPhone((trim($request->request->get('phone'))))
            ->setEmail((trim($request->request->get('email'))))
            ->setAdditionalInformation((trim($request->request->get('aditionalInformation'))));

        return $customUser;
        }
        return  null;
    }
    public function AssignDataToNewClient(Request $request): CustomUser
    {
        $newCustomUser = new CustomUser();
        if (intval(trim($request->request->get('country'))) == 1){
            return $this->AssignDataToCustomUserEntity($newCustomUser,$request);
        }

        return $this->AssignDataToCustomUserEntityUnknownCountry($newCustomUser, $request);

    }
    public function UpdateDataClient(CustomUser $customUser,Request $request): CustomUser
    {
//        if (intval(trim($request->request->get('country'))) == 1){
            return $this->AssignDataToCustomUserEntity($customUser,$request);
     /*   }

        return $this->AssignDataToCustomUserEntityUnknownCountry($customUser, $request);*/
    }

    public function AssingDataToNewClientDiscount(CustomUser $customUser, Request $request)
    {
       // $newUserDiscount = new ClientDiscount();
        return $this->AssignDataToClientDiscountEntity(new ClientDiscount(),$customUser, $request);
    }

    public function UpdateDataClientDiscount(ClientDiscount $clientDiscount,CustomUser $customUser, Request $request)
    {
        return $this->AssignDataToClientDiscountEntity($clientDiscount,$customUser, $request);
    }

    public function AssignDataToClientDiscountEntity(ClientDiscount $clientDiscount,CustomUser $customUser, Request $request)
    {
        try {
            $clientDiscount->setCustomUser($customUser)
                ->setProductTypeDiscount($this->em->getRepository(ProductType::class)->find($request->request->get('productTypeId')))
                ->setDiscount(intval($request->request->get('discount')));
        }
        catch (\Exception $exception){
            $this->addFlash('fail','No se ha podido asignar el descuento.');
            $clientDiscount = null;
        }


        return $clientDiscount;

    }
}
