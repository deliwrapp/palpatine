<?php

namespace App\Security\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Security\Controller
 */
class SecurityController extends AbstractController
{

    /**
     * Login User
     * 
     * @param AuthenticationUtils $authenticationUtils
     * @Route("/login", name="app_login")
     * @return RedirectResponse
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@security/user/anonymous/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * loginRedirect
     * Redirect to admin or homepage depending of the role
     * 
     * @Route("/login-redirect", name="login_redirect")
     * @return RedirectResponse
     */
    public function loginRedirect(): RedirectResponse
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_EDITOR') || $this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('admin_dashboard_redirect');
        }
        return $this->redirectToRoute('homepage');         
    }
    
    /**
     * Logout
     * 
     * @Route("/logout", name="app_logout")
     * @return void
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
}
