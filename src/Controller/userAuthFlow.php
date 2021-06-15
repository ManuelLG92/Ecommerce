<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class userAuthFlow extends  AbstractController
{

    /**
     * @Route ("/flow-users/", name="auth_users_flow", methods={"GET"})
     */
    public function RedirectAuthenticationUserByRole(): RedirectResponse
    {
        $roles = $this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN", $roles)){
            return $this->redirectToRoute('admin_index');
        }
        return $this->redirectToRoute('public_agent_profile');

    }
}