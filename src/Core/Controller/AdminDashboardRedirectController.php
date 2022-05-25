<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminDashboardRedirectController -- Redirect to Admin depending on the user rôle page
 * @package App\Core\Controller
 */
class AdminDashboardRedirectController extends AbstractController
{

    /**
     * Redirect to Administration Dashboard page depending of the role
     * 
     * @Route("/access-redirect", name="admin_dashboard_redirect")
     * @return RedirectResponse
     */
    public function adminRedirect(): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        if ($this->isGranted('ROLE_EDITOR')) {
            return $this->redirectToRoute('editor_dashboard');
        }
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('moderator_dashboard');
        }
        $this->addFlash(
            'warning',
            'Unauthorized Access'
        );
        return $this->redirect($this->generateUrl('page_error_handler', [
            'error_code' => 401
        ]));
    }

}
