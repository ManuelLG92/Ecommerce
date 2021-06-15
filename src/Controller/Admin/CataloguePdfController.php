<?php

namespace App\Controller\Admin;

use App\Entity\Catalogue;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/catalogues")
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class CataloguePdfController extends AbstractController
{
    private SluggerInterface $slugger;
    private EntityManagerInterface $em;
    function __construct(SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $this->slugger= $slugger;
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin_get_catalogues", methods={"GET"})
     */
    public function index(): Response
    {
        $extraCatalogues = $this->em->getRepository(Catalogue::class)->findAll();
        return $this->render('admin/catalogues/index.html.twig', [
            'title' => 'Catálogos PDF',
            'extra_catalogues' => $extraCatalogues,
        ]);
    }

    /**
     * @Route("/edit", name="edit_catalogue_put",  methods={"PUT"} )
     */
    public function EditCataloguePut(Request $request): Response
    {
        $file = $request->files->get('pdf');
        $name = $request->request->get('name');

        if ($file) {
            $fs = new Filesystem();
            if (strcmp('estilker', $name) == 0) {

                try {
                    if ($fs->exists($this->getParameter('directory_catalogue_pdf') . '/estilker.pdf')){
                        $fs->remove($this->getParameter('directory_catalogue_pdf') . '/estilker.pdf');
                    }
                    $file->move($this->getParameter('directory_catalogue_pdf'), 'estilker.pdf');
                } catch (\Exception $exception) {
                    $this->addFlash('fail', 'Has been an error updating Estilker PDF.');
                }
            }

            if (strcmp('lastdecor', $name) == 0) {
                try {
                    if ($fs->exists($this->getParameter('directory_catalogue_pdf') . '/lastdecor.pdf')){
                        $fs->remove($this->getParameter('directory_catalogue_pdf') . '/lastdecor.pdf');
                    }
                    $file->move($this->getParameter('directory_catalogue_pdf'), 'lastdecor.pdf');
                } catch (\Exception $exception) {
                    $this->addFlash('fail', 'Has been an error updating lastdecor PDF.');

                }
            }

        } else {
            $this->addFlash('fail', 'PDF file could not be recognized');

        }
        return $this->render('admin/catalogues/index.html.twig', [
            'title' => 'Catálogos PDF',
        ]);
    }

    /**
     * @Route("/", name="admin_add_extra_catalogue_post", methods={"POST"})
     */
    public function createExtraCatalogue(Request $request): Response
    {
        //dd($request->request->all());
        $this->GetDataNewCatalogue($request);
        return $this->redirectToRoute('admin_get_catalogues');
    }

    /**
     * @Route("/{id<\d+>}", name="admin_edit_extra_catalogue_put", methods={"PUT"})
     */
    public function editExtraCatalogue(Catalogue $catalogue, Request $request): Response
    {
        if ($this->GetFileFromRequest($request)){
            $this->UpdateCatalogue($catalogue, $request);
        } else {
            $this->SaveCatalogueOnDatabase($this->GetOnlyAgentVisibility($request,$catalogue));
        }

        return $this->redirectToRoute('admin_get_catalogues');
    }

    /**
     * @param Request $request
     * @Route ("/delete/{id<\d+>}",name="delete_extra_catalogue", methods={"DELETE"})
     */
    public function DeleteExtraCatalogue(Catalogue $catalogue, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$catalogue->getId(), $request->request->get('_token'))){

        $fs = new Filesystem();
        try {
            if ($fs->exists($this->getParameter('directory_catalogue_pdf') . '/' . $catalogue->getUrl())){
                $fs->remove($this->getParameter('directory_catalogue_pdf')  . '/' .  $catalogue->getUrl());
                $this->em->remove($catalogue);
                $this->em->flush();
                $this->addFlash('success', 'Archivo eliminado.');
            } else {
                $this->addFlash('fail', 'Archivo no encontrado en el servidor.');
            }
        } catch (\Exception $exception) {
            $this->addFlash('fail', 'Ha ocurrido un error eliminando la imagen del servidor.');
        }

        } else {
            $this->addFlash('fail','Token CSRF no válido.');
        }
        return $this->redirectToRoute('admin_get_catalogues');

    }

    public function GetDataNewCatalogue(Request $request)
    {
        $catalogue = new Catalogue();
        if ($catalogue = $this->GetCatalogueDataFromRequest($catalogue,$request)){
            if ($this->SaveCatalogueOnDatabase($catalogue)){
                $this->addFlash('success','Catálogo agregado.');
                return $catalogue;
            }
        } else {
            $this->addFlash('fail','No se pudieron obtener los datos en la solicitud');
            return null;
        }

        return $this->redirectToRoute('admin_get_catalogues');
    }

    public function UpdateCatalogue(Catalogue $catalogue, Request $request): ?Catalogue
    {

        if($catalogueUpdated = $this->GetCatalogueDataFromRequest($catalogue, $request)){
            if ($this->SaveCatalogueOnDatabase($catalogueUpdated)){
                $this->addFlash('success','Catálogo Editado.');
                return $catalogueUpdated;
            } else {
                return null;
            }
        } else {
            $this->addFlash('fail','No se pudieron obtener los datos en la solicitud');
            return null;
        }
    }

    public function GetCatalogueDataFromRequest(Catalogue $catalogue, Request $request): ?Catalogue
    {

            if ($url = $this->SaveCatalogueOnServer($catalogue,$request)){
                $catalogue->setUrl($url);
                $request->request->get('agent') ?  $catalogue->setHasAgentVisibility(true) :  $catalogue->setHasAgentVisibility(false);

                return $catalogue;
            }
            return null;
    }

    public function GetOnlyAgentVisibility(Request $request, Catalogue $catalogue): Catalogue
    {
        $request->request->get('agent') ?  $catalogue->setHasAgentVisibility(true) :  $catalogue->setHasAgentVisibility(false);
        return $catalogue;
    }



    public function GetFileFromRequest(Request $request)
    {
        return $request->files->get('catalogue');
    }

    public function SaveCatalogueOnDatabase(Catalogue $catalogue): bool
    {
        try {
            $this->em->persist($catalogue);
            $this->em->flush();
            return true;
        }catch (FileException $e) {
            $this->addFlash('fail','No ha sido posible guardar el archivo en la base de datos.');
            return false;
        }
    }


    public function SaveCatalogueOnServer(Catalogue $catalogue, Request $request): ?string
    {

        if ($catalogueFile = $this->GetFileFromRequest($request)){
            $originalFilename = pathinfo($catalogueFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-id-'.uniqid().'.'.$catalogueFile->guessExtension();
            try {
            $catalogueFile->move($this->getParameter('directory_catalogue_pdf'), $newFilename);
            return $newFilename;
            }catch (FileException $e) {
              $this->addFlash('fail','No ha sido posible guardar el archivo.');
            }
        } else {
            $this->addFlash('fail','No se pudo obtener el archivo');
        }
        return null;
    }
}
