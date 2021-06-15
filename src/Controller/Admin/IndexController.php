<?php


namespace App\Controller\Admin;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UseController
 * @package App\Controller
 * @Route ("/admin")
 */
class IndexController extends AbstractController
{

    /**
     * @Route ("/", name="admin_index", methods={"GET"})
     */
    public function AdminIndex()
    {
        return  $this->redirectToRoute('get_all_products');

    }


}