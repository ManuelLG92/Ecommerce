<?php

namespace App\Controller\Site;

use App\Entity\Format;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\User;
use App\Entity\Zone;
use App\Security\Mailer;
use App\Services\GlobalManager;
use App\Services\SessionsHandler;
use App\Services\SiteManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Glob;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IndexController extends AbstractController
{
    private int $ELEMENTS_BY_PAGE = 6;
    private int $ELEMENTS_BY_PAGE_OUTLET = 4;
    private $em;
    private $siteManager;
    private $encoder;
    private $globalManager;


    function __construct(EntityManagerInterface $em,  SiteManager  $siteManager, UserPasswordEncoderInterface $encoder, GlobalManager $globalManager)
    {
        $this->em = $em;
        $this->siteManager= $siteManager;
        $this->encoder = $encoder;
        $this->globalManager = $globalManager;
    }

    /**
     * @Route ("/", name="home", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('site/index.html.twig', [
            'title' => 'Estilker Colombia SAS',
        ]);
    }

    /**
     * @Route ("/contact", name="contact", methods={"GET"})
     */
    public function Contact(): Response
    {
        return $this->render('site/contact.html.twig', [
            'title' => 'Contacto',
        ]);
    }
    /**
     * @Route ("/send-contact-form", name="send_contact_form", methods={"POST"})
     */
    public function SendContactForm(Request $request, Mailer $mailer, ValidatorInterface $validator): RedirectResponse
    {
        $contactFormData = $this->siteManager->setDataToContactFormDTOFromRequest($request);

        if (strcmp($request->request->get('checkBox'),'on') == 0){
            $bodyData = [
                'name'=>$contactFormData->getName(),
                'surname' => $contactFormData->getSurname(),
                'contact_email' => $contactFormData->getEmail(),
                'phone' => $contactFormData->getPhone(),
                'city' => $contactFormData->getCity(),
                'region' => $contactFormData->getRegion(),
                'country' => $contactFormData->getCountry(),
                'additional_info' => $contactFormData->getAdditionalInfo()];

            if( $mailer->sendEmail($this->getParameter('app_email_estilker'),'Contacto','common/mailer_contact_template.html.twig',$bodyData)){
                $this->addFlash('success','Formulario de contacto enviado, nos pondremos en contacto lo mas pronto posible!');
                return $this->redirectToRoute('home');
            }
            $this->addFlash('fail','Ha ocurrido un error enviando el formulario, intentalo en unos minutos.');
            return $this->redirectToRoute('contact');
        }
        $this->addFlash('fail','Debes aceptar los terminos y condiciones');
        return $this->redirectToRoute('contact');

    }



    /**
     * @Route ("/us", name="us", methods={"GET"})
     */
    public function Us(): Response
    {
        return $this->render('site/us.html.twig', [
            'title' => 'Quienes somos',
        ]);
    }

    /**
     * @Route ("/outlet/{page<\d+>}", name="outlet",
     *     defaults = {
     *     "page" = 1 },
     *     methods={"GET"})
     */
    public function Outlet(int $page): Response

    {
        $outletSectionId = $this->em->getRepository(ProductType::class)->findOneBy(['name' => 'Outlet'])->getId();
        $productsOutlet = $this->em->getRepository(Product::class)->CountOutletProducts($outletSectionId);

        $numberPages =  $this->siteManager->GetOutletPageNumbers($productsOutlet);

        if ($page < 1 ) {
            return $this->redirectToRoute('outlet');
        }
        if ($page > $numberPages ) {
            return $this->redirectToRoute('outlet', ['page' => $numberPages]);
        }
        $products = $this->em->getRepository(Product::class)->GetOutletProducts($page, $this->ELEMENTS_BY_PAGE_OUTLET);

        $selectedZone = 0;
        $selectedMaterial = 0;
        $selectedFormat = [];
        list($zones, $materials, $formats) = $this->siteManager->getZonesMaterialsAndFormats();

        return $this->render('site/product/outlet/outlet.html.twig', [
            'title' => 'Outlet',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $productsOutlet,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materials,
            'selectedZone' => $selectedZone,
            'selectedMaterials' => $selectedMaterial,
            'selectedFormats' => $selectedFormat,
        ]);
    }

    /**
     * @Route ("/catalogue/{page<\d+>}", name="catalogue",
     *     defaults = {
     *     "page" = 1 },
     *      methods={"GET"})
     */
    public function Catalogue(int $page): Response

    {
        $countAllProducts= $this->em->getRepository(Product::class)->CountProducts();
        $numberPages =  $this->siteManager->GetPageNumbers($countAllProducts);
        list($zones, $materials, $formats) = $this->siteManager->getZonesMaterialsAndFormats();

        if ($page < 1 ) {
            return $this->redirectToRoute('catalogue');
        }

        if ($page > $numberPages ) {
            return $this->redirectToRoute('catalogue', ['page' => $numberPages]);
        }

        $selectedZone = 0;
        $selectedMaterial = 0;
        /*foreach ($materials as $material) {
            $selectedMaterial[] = $material->getId();
        }*/
        $selectedFormat = [];
        /*foreach ($formats as $format) {
            $selectedFormat[] = $format->getId();
        }*/
        $products = $this->em->getRepository(Product::class)->GetAllProducts($page, $this->ELEMENTS_BY_PAGE);
        return $this->render('site/product/products_all.html.twig', [
            'title' => 'Catalogo',
            'products' => $products,
            'current_page' => $page,
            'number_pages' => $numberPages,
            'totalProducts' => $countAllProducts,
            'zones' => $zones,
            'formats' => $formats,
            'materials' => $materials,
            'selectedZone' => $selectedZone,
            'selectedMaterials' => $selectedMaterial,
            'selectedFormats' => $selectedFormat,
        ]);
    }




}

