<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminDashboardController
 * @package App\Core\Controller\Admin
 * @Route("/admin")
 */
class AdminDashboardController extends AbstractController
{
    /**
     * Admin Dashboard Home
     * 
     * @Route("/", name="AdminDashboard") 
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/dashboard/main.html.twig', []);
    }

}
