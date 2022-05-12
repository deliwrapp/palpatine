<?php

namespace App\Security\Controller;

use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use App\Security\Form\AdminUserType;
use App\Security\Form\AdminUserPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class AdminUserController  - Admin User Manager
 * @package App\Security\Controller
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    
    /** @var UserPasswordHasherInterface */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * User List Index
     * 
     * @param UserRepository $userRepository
     * @Route("/", name="admin_user_index", methods={"GET"})
     * @return Response
     * @return RedirectResponse
     */
    public function index(UserRepository $userRepository): Response
    {
        try {
            return $this->render('@security-admin/user/user-index.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }
    }

    /**
     * Create User
     * 
     * @param UserRepository $userRepository
     * @param Request $request
     * @Route("/new", name="admin_user_create", methods={"GET", "POST"})
     * @return Response
     * @return RedirectResponse
     */
    public function create(UserRepository $userRepository, Request $request): Response
    {
        try {
            $user = new User();
            $form = $this->createForm(AdminUserType::class, $user, [
                'mode' => 'create'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $plaintextPassword = $form->get('password')->getData();
                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user);

                $this->addFlash(
                    'info',
                    'Saved new user with id '.$user->getId()
                );
                return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('@security-admin/user/user-create.html.twig', [
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_user_list'));
        }
    }

    /**
     * Edit User
     * 
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET", "POST"})
     * @return Response
     * @return RedirectResponse
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        try {
            $form = $this->createForm(AdminUserType::class, $user, [
                'mode' => 'edit'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $userRepository->add($user);
                $this->addFlash(
                    'info',
                    'Updated new user with id '.$user->getId()
                );
                return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('@security-admin/user/user-edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }
    }

    /**
     * Edit User Password
     * 
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @Route("/{id}/edit-password", name="admin_user_password_edit", methods={"GET", "POST"})
     * @return Response
     * @return RedirectResponse
     */
    public function editPassword(Request $request, User $user, UserRepository $userRepository): Response
    {
        try {
            $form = $this->createForm(AdminUserPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $plaintextPassword = $form->get('password')->getData();
                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user);
                
                $this->addFlash(
                    'info',
                    'Updated new user with id '.$user->getId()
                );
                return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('@security-admin/user/user-edit-password.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }
    }

    /**
     * Show User (Admin View)
     * 
     * @param User $user
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     * @return Response
     * @return RedirectResponse
     */
    public function show(User $user): Response
    {
        try {
            return $this->render('@security-admin/user//user-show.html.twig', [
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }
    }

    /**
     * Delete User
     * 
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @Route("/{id}", name="admin_user_delete", methods={"POST"})
     * @return RedirectResponse
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): RedirectResponse
    {
        try {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user);
                $this->addFlash(
                    'info',
                    'User have been deleted id '
                );
            }
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }
    }
}
