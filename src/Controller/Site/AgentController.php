<?php

namespace App\Controller\Site;


use App\Entity\Catalogue;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/")
 */
class AgentController extends AbstractController

{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted ("ROLE_AGENT")
     * @Route ("profile/agent", name="public_agent_profile", methods={"GET"})
     */
    public function profile(): Response
    {
        $agentCatalogues = $this->em->getRepository(Catalogue::class)->findBy(['hasAgentVisibility' => 1]);
        return $this->render('site/profile/agent_profile.html.twig', [
            'title' => 'Zona de agentes',
            'agent_catalogues' => $agentCatalogues,
        ]);
    }






}

