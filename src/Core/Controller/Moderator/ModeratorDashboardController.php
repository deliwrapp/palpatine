<?php

namespace App\Core\Controller\Moderator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ModeratorDashboardController
 * @package App\Core\Controller\Moderator
 * @IsGranted("ROLE_MODERATOR",statusCode=401, message="No access! Get out!")
 * @Route("/moderator")
 */
class ModeratorDashboardController extends AbstractController
{
    /**
     * Moderator Dashboard Home
     * 
     * @Route("/", name="moderator_dashboard") 
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/dashboard/moderator.html.twig', []);
    }

}
