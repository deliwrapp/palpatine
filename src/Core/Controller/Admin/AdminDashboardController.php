<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminDashboardController
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
 * @Route("/admin")
 */
class AdminDashboardController extends AbstractController
{
    /**
     * Admin Dashboard Home
     * 
     * @Route("/", name="admin_dashboard") 
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/dashboard/main.html.twig', []);
    }

}
