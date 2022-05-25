<?php

namespace App\Security\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\Repository\UserRepository;
use App\Security\Form\UserAccountFormType;

/**
 * Class MemberUserAccountController
 * @package App\Security\Controller
 * @Route("/account")
 */
class MemberUserAccountController extends AbstractController
{
    /** @var UserPasswordHasherInterface */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    /**
     * User Account Page
     * 
     * @Route("/", name="member_my_account")
     * @return Response
     */
    public function myAccount(): Response
    {
        try {
            $user = $this->getUser();
            return $this->render('@security/user/member/account-show.html.twig', [
                'user' => $user
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * User Edit Account Page
     * 
     * 
     * @param Request $request
     * @param UserRepository $userRepository
     * @Route("/edit-my-account", name="member_edit_my_account", methods={"GET", "POST"})
     * 
     * @return Response
     * @return RedirectResponse
     */
    public function editMyAccount(Request $request, UserRepository $userRepository): Response
    {
        try {
            $user = $this->getUser();
            $form = $this->createForm(UserAccountFormType::class, $user, [
                'mode' => 'edition'
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->add($user);
                $this->addFlash(
                    'info',
                    'Account Updated'
                );
                return $this->redirectToRoute('member_edit_my_account', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('@security/user/member/account-edit.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }   
    }

    /**
     * User Edit Account Page
     * 
     * 
     * @param Request $request
     * @param UserRepository $userRepository
     * @Route("/edit-my-email", name="member_edit_my_email", methods={"GET", "POST"})
     * 
     * @return Response
     * @return RedirectResponse
     */
    public function editMyEmail(Request $request, UserRepository $userRepository): Response
    {
        try {
            $user = $this->getUser();
            $form = $this->createForm(UserAccountFormType::class, $user, [
                'mode' => 'edit-email'
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->add($user);
                $this->addFlash(
                    'info',
                    'Email Updated'
                );
                return $this->redirectToRoute('member_my_account', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('@security/user/member/email-edit.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }   
    }

    /**
     * User Edit Password Page
     * 
     * @param Request $request
     * @param UserRepository $userRepository
     * @Route("/update-my-password", name="member_edit_my_password", methods={"GET", "POST"})
     * 
     * @return Response
     * @return RedirectResponse
     */
    public function editMyPassword(Request $request, UserRepository $userRepository): Response
    {
        try {
            $user = $this->getUser();
            $form = $this->createForm(UserAccountFormType::class, $user, [
                'mode' => 'edit-password'
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
                    'Saved new password'
                );
                return $this->redirectToRoute('member_my_account', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('@security/user/member/password-edit.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
        
    }
}
