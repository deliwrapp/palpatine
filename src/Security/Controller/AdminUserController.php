<?php

namespace App\Security\Controller;

use App\Security\Entity\User;
use App\Security\Form\AdminUserType;
use App\Security\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="AdminUsersList", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('@security/user/admin/user-index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_app_user_create", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@security/user/admin/user-create.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('@security/user/admin/user-show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@security/user/admin/user-edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $doctrine->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
