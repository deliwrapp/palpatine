<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class EditorDashboardController
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor")
 */
class EditorDashboardController extends AbstractController
{
    /**
     * Editor Dashboard Home
     * 
     * @Route("/", name="editor_dashboard") 
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@core-admin/dashboard/editor.html.twig', []);
    }

}
