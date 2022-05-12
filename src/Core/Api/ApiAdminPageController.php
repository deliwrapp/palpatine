<?php

namespace App\Core\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Entity\Block;
use App\Core\Entity\PageBlock;
use App\Core\Repository\PageRepository;
use App\Core\Form\PageFormType;

/**
 * @Route("/admin/page")
 */
class ApiAdminPageController extends AbstractController
{

    /**
     * Api Admin Page List Index
     * 
     * @param PageRepository $pageRepo
     * @Route("/", name="ApiAdminPageList")
     * @return Response
     */
    public function index(PageRepository $pageRepo): Response
    {
        $pages = $pageRepo->findAll();

        return $this->render('@core-admin/page/page-list.html.twig', [
            'pages' => $pages
        ]);
    }

}
