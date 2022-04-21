<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use App\Core\Services\PageVerificator;

/**
 * @Route("/admin/page")
 */
class AdminSubPageController extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var PageVerificator */
    private $pageVerif;

    /** @var ManagerRegistry */
    private $em;

    public function __construct(
        PageRepository $pageRepo,
        PageVerificator $pageVerif,
        ManagerRegistry $em
    )
    {
        $this->pageRepo= $pageRepo;
        $this->pageVerif= $pageVerif;
        $this->em = $em->getManager();
    }

    // Page Add SubPage
    /**
     * @Route("/add-subpage-to/{id}", name="admin_subpage_add")
     */
    public function addSubpage(int $id, PageRepository $pageRepo): Response
    {
        $page = $this->pageRepo->find($id);

        return $this->render('@core-admin/page/page-show.html.twig', [
            'page' => $page
        ]);
    }


    // Page Remove SubPage


    // Page Link As a Subpage


    // SubPage Remove Link


}
