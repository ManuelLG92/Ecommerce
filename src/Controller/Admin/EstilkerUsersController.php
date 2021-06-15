<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DecoratedController
 * @package App\Controller
 * @IsGranted("ROLE_SUPER_ADMIN")
 * @Route ("/admin/estilker-users")
 */
class EstilkerUsersController extends AbstractController
{
    private EntityManagerInterface $em;
    private $encoder;
    private User $singletonUser;

    function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->singletonUser = new User();
    }

    /**
     * @Route ("/",name="get_all_estilker_users", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->render('admin/estilker_users/index.html.twig', [
            'title' => 'Usuarios Estilker',
            'controller_name' => 'EstilkerUsersController',
            'users' => $users,
            'roles' => $this->getAllRoles(),
        ]);
    }

    /**
     * @Route ("/create/", name="create_estilker_user_get", methods={"GET"})
     */
    public function CreateEstilkerUserGet(): Response
    {
        return $this->render('admin/estilker_users/create.html.twig', [
            'title' => 'Crear Usuario Estilker',
            'user' => new User(),
            'roles' => $this->getAllRoles(),
        ]);
    }

    /**
     * @Route ("/create-post/", name="create_estilker_user_post", methods={"POST"})
     */
    public function CreateEstilkerUserPost(Request $request): RedirectResponse
    {

        $user = $this->getNewUserDataFromRequest($request);
        if ($this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()])){
            $this->addFlash('fail', 'Ya existe un usuario registrado con el email: "' . $user->getEmail() . '".');
            return $this->redirectToRoute('get_all_estilker_users');
        }

        try {
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Usuario ' . $user->getEmail() . ' creado.');
        } catch (\Exception $e) {
            $this->addFlash('fail', 'Ocurrio un error creando al usuario ' . $user->getEmail() . '.');
        }

        return $this->redirectToRoute('get_all_estilker_users');

    }

    /**
     * @Route ("/edit/{id<\d+>}", name="edit_estilker_user_get", methods={"GET"})
     */
    public function EditEstilkerUserGet(User $user): Response
    {

        return $this->render('admin/estilker_users/edit.html.twig', [
            'title' => 'Editar Usuario Estilker',
            'user' => $user,
            'roles' => $this->getAllRoles(),
        ]);
    }

    /**
     * @Route ("/edit-put/", name="edit_estilker_user_put", methods={"PUT"})
     */
    public function EditEstilkerUserPut(Request $request): RedirectResponse
    {
       // dd($request->request->all());
        $user = $this->em->getRepository(User::class)->find((int)trim($request->request->get('idUser')));
        if (!$user){
            $this->addFlash('fail', 'Usuario no encontrado.');
            return $this->redirectToRoute('get_all_estilker_users');
        }

        $userUpdated = $this->UpdateEstilkerUser($user, $request);

            try {
                $this->em->persist($userUpdated);
                $this->em->flush();
                $this->addFlash('success', 'Usuario "' . $user->getEmail() . '" editado.');
            } catch (\Exception $e) {
                $this->addFlash('fail', 'Ocurrio un error editando al usuario "' . $user->getEmail() . '".');
            }

        return $this->redirectToRoute('get_all_estilker_users');

    }

    /**
     * @Route ("/delete/{id<\d+>}", name="delete_estilker_user", methods={"DELETE"})
     */
    public function DeleteEstilkerUser(User $user, Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->addFlash('fail', 'CSRF Token no válido.');
            return $this->redirectToRoute('get_all_estilker_users');
        }

        try {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Usuario "' . $user->getEmail() . '" eliminado.');
        } catch (\Exception $e) {
            $this->addFlash('fail', 'No se pudo eliminar el usuario "' . $user->getEmail() . '".');
        }
        return $this->redirectToRoute('get_all_estilker_users');
    }


    public function getNewUserDataFromRequest(Request $request): User
    {
        return $this->AssignDataToUser(new User(), $request);

    }

    public function AssignDataToUser(User $user, Request $request): User
    {
        return $user
            ->setEmail(strtolower(trim($request->request->get('email'))))
            ->setPassword($this->encoder->encodePassword($user, trim(($request->request->get('password')))))
            ->setEmail(trim($request->request->get('email')))
            ->setRoles($this->GetRolesForUser(trim($request->request->get('role'))));
    }

    public function GetRolesForUser($role): array
    {

        switch ($role) {
            case 2:
                $roles = $this->singletonUser->getAgentRoles();
                break;
            case 3:
                $roles = $this->singletonUser->getAdminRoles();
                break;
            case 4:
                $roles = $this->singletonUser->getProductManagerRoles();
                break;
            case 5:
                $roles = $this->singletonUser->getSuperAdminRoles();
                break;
            default:
                $roles = $this->singletonUser->getRoles();
                break;
        }

        return $roles;


    }

    public function UpdateEstilkerUser(User $user,Request $request): User
    {
        if ($request->request->get("changePassword")){
            $userUpdated = $this->AssignDataToUser($user, $request);
        } else {
            $userUpdated = $this->updateUserEmailAndRole($user, $request);
        }
        return $userUpdated;

    }

    public function updateUserEmailAndRole(User $user, Request $request): User
    {
        return $user->setEmail($request->request->get("email"))
            ->setRoles($this->GetRolesForUser(trim($request->request->get('role'))));
    }

    public function getAllRoles(): array
    {
        $user = new User();
        return $user->getSuperAdminRoles();
    }
}
