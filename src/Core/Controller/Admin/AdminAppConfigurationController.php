<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminAppConfigurationController
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
 * @Route("/admin/app")
 */
class AdminAppConfigurationController extends AbstractController
{
    /**
     * Admin App configuration Dashboard
     * 
     * @Route("/", name="admin_app_configuration_dashboard") 
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/app/admin/dashboard.html.twig', []);
    }

}
