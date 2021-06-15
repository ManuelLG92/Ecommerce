<?php


namespace App\Controller\Admin;


use App\Entity\Zone;
use App\Services\ZoneManager;
use App\Services\GlobalManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ZoneController
 * @package App\Controller
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route ("/admin/zone")
 */
class ZoneController extends AbstractController
{
    private EntityManagerInterface $em;
    private ZoneManager $zoneManager;
    private GlobalManager $globalManager;

    private $message;

    public static string $successOperation = "success_message";
    public static string $failOperation = "fail_message";

    function __construct(EntityManagerInterface $entityManager, ZoneManager $categoryManager, GlobalManager $globalManager){

        $this->em = $entityManager;
        $this->zoneManager = $categoryManager;
        $this->globalManager = $globalManager;
    }

    /**
     * @Route ("/",name="get_all_zones", methods={"GET"})
     */
    public function GetAllZones(): Response
    {
        $zones = $this->em->getRepository(Zone::class)->findAll();
        $zone = new Zone();
        return $this->render('admin/zone/index.html.twig', [
            'title' => 'Zonas',
            'zones' => $zones,
            'zone' => $zone,
        ]);
    }

    /**
     * @Route ("/create",name="create_zone_get", methods={"GET"})
     */
    public function CreateZoneGet(): Response
    {
        $zone = new Zone();
        return $this->render('admin/zone/new_zone.html.twig', [
            'title' => 'Crear Zona',
            'category' => $zone,
        ]);
    }

    /**
     * @Route ("/create",name="create_zone_post", methods={"POST"})
     */
    public function CreateZonePost(Request $request): RedirectResponse
    {

        $zone = $this->zoneManager->AssingDataToZone($request);
        $errorsValidation = $this->zoneManager->ValidateZoneFields($zone);

        if ($this->globalManager->CheckErrorsOnValidation($errorsValidation)){
            return  $this->redirectToRoute('create_zone_get');
        }
        $newCategory = $this->zoneManager->CreateOrUpdateZone($zone);

        if (!$newCategory){

            $this->addFlash($this->getParameter(self::$failOperation),"La Zona no se ha podido crear.");
            return  $this->redirectToRoute('create_zone_get');
        }

        $this->addFlash($this->getParameter(self::$successOperation),"Zona creada satisfactoriamente.");
        return  $this->redirectToRoute('get_all_zones');

    }

    /**
     * @Route ("/edit/{id<\d+>}",name="edit_zone_get", methods={"GET"})
     */
    public function EditZoneGet(int $id): Response
    {
        $zone = $this->em->getRepository(Zone::class)->find($id);

        if (!$zone){
            $this->message = "La zona  #" . $id . " no ha sido encontrada.";
            $this->addFlash($this->getParameter(self::$failOperation),$this->message);
            return $this->redirectToRoute("get_all_zones");
        }
        return $this->render('admin/zone/edit_zone.html.twig', [
            'title' => 'Editar Zona',
            'zone' => $zone,
        ]);
    }



    /**
     * @Route ("/edit}",name="edit_zone_put", methods={"PUT"})
     */
    public function EditZonePut(Request $request) :Response
    {

        $id = $request->request->get('idZone');

        $zone = $this->zoneManager->GetZone($request);

        if (!$zone){
            $this->addFlash($this->getParameter(self::$failOperation),"La zona #" . $id . " no ha sido encontrada.");
            return $this->redirectToRoute('get_all_zones');
        }

        $zone->setName(trim($request->request->get('name')));

        $errorsValidation = $this->zoneManager->ValidateZoneFields($zone);

        if ($this->globalManager->CheckErrorsOnValidation($errorsValidation)){
            return  $this->redirectToRoute('edit_zone_get', ['id'=> intval($id)]);
        }

        $zonaEdited = $this->zoneManager->CreateOrUpdateZone($zone);

        if (!$zonaEdited){
            $this->addFlash($this->getParameter(self::$failOperation),"Ha habido un problema editando la zona " . $zone->getName() . ". Intentelo mas tarde.");
            return  $this->redirectToRoute('edit_zone_get', ['id'=> $id]);
        }
        $this->addFlash($this->getParameter(self::$successOperation),"Zona " . $zone->getName() . " editada exitosamente.");
        return $this->redirectToRoute('get_all_zones');

    }

    /**
     * @Route ("/delete/{id<\d+>}", name="delete_zone")
     */
    public function DeleteZone(Request $request, int $id): RedirectResponse
    {
        $zone = $this->em->getRepository(Zone::class)->find($id);

        if (!$zone){
            $this->addFlash($this->getParameter(self::$failOperation),"La zona #" . $id . " no ha sido encontrada.");
            return $this->redirectToRoute('get_all_zones');
        }
        if (!$this->CheckCsrfToken('delete'.$zone->getId(), $request)){
            $this->addFlash($this->getParameter(self::$failOperation),"Token CSRF no valido.");
            return $this->redirectToRoute('get_all_zones');
        }
        if ($this->zoneManager->DeleteZone($zone)){
            $this->addFlash($this->getParameter(self::$successOperation),"Zona " . $zone->getName() . ' eliminada.');
            return $this->redirectToRoute('get_all_zones');
        }
        $this->addFlash($this->getParameter(self::$failOperation),"La zona " . $zone->getName() . ' No se pudo eliminar.');
        return $this->redirectToRoute('get_all_zones');
    }

    public function CheckCsrfToken(string $idCsrf, Request $request)
    {

        $this->isCsrfTokenValid($idCsrf, $request->request->get('_token')) ? $validCsrf = true : $validCsrf = false;

        return $validCsrf;
    }



}