<?php


namespace App\Services;


use App\Entity\ContactFormDTO;
use App\Entity\Format;
use App\Entity\Material;
use App\Entity\Zone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SiteManager
{
    private $em;
    private $ELEMENTS_BY_PAGE = 6;
    private $ELEMENTS_BY_PAGE_OUTLET = 4;
    function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

    }

    public function GetPageNumbers(int $productsCount ) : int
    {
        if ($productsCount > $this->ELEMENTS_BY_PAGE){
            $numberPages = ceil($productsCount/$this->ELEMENTS_BY_PAGE);
        }  else {
            $numberPages = 1;
        };

        return $numberPages;
    }

    public function GetOutletPageNumbers(int $productsCount ) : int
    {
        if ($productsCount > $this->ELEMENTS_BY_PAGE_OUTLET){
            $numberPages = ceil($productsCount/$this->ELEMENTS_BY_PAGE_OUTLET);
        }  else {
            $numberPages = 1;
        };

        return $numberPages;
    }

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


    public function getFormatsToFilterFromRequest($formats)
    {
        return explode("-", $formats);
    }

    public function getMaterialIdToFilter(Material $material): ?int
    {
        return $material->getId();
    }

    public function getZoneMaterialAndFormatFromRequest(Request $request): array
    {
        $zone = $request->request->get('zone');
        $material = $request->request->get('material');
        $formats = $request->request->get('format');
        return array($zone, $material, $formats);
    }
    public function getZoneMaterialandFormatLengthFromRequest($material, string $formatsImploded, $zone): array
    {
        $materialsLength = strlen($material);
        $formatsImplodedLength = strlen($formatsImploded);
        $zoneLength = strlen($zone);
        return array($materialsLength, $formatsImplodedLength, $zoneLength);
    }

    public function getFormatsImploded($formats): string
    {
        $formats ? $formatsImploded = implode("-", $formats) : $formatsImploded = '';
        return $formatsImploded;
    }

    public function setDataToContactFormDTOFromRequest(Request $request): ContactFormDTO
    {
        $contactFormInstance = new ContactFormDTO();
        return $contactFormInstance
            ->setName(trim($request->request->get('name')))
            ->setSurname(trim($request->request->get('surname')))
            ->setPhone(trim((int)$request->request->get('phone')))
            ->setEmail(trim($request->request->get('email')))
            ->setCountry(trim($request->request->get('country')))
            ->setRegion(trim($request->request->get('region')))
            ->setCity(trim($request->request->get('city')))
            ->setAdditionalInfo(trim($request->request->get('additionalInfo')));
    }

}