<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use App\Core\Services\PageVerificator;

/**
 * Class AdminSubPageController -- Manage Subpage
 * @package App\Core\Controller\Admin
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
    /**
     * Page Add SubPage
     * 
     * @param int $id
     * @param PageRepository $pageRepo
     * @Route("/add-subpage-to/{id}", name="admin_subpage_add")
     * @return Response
     * @return RedirectResponse
    */
    public function addSubpage(int $id, PageRepository $pageRepo): Response
    {
        try {
            $page = $this->pageRepo->find($id);
            return $this->render('@core-admin/page/page-show.html.twig', [
                'page' => $page
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        }  
    }


    // Page Remove SubPage


    // Page Link As a Subpage


    // SubPage Remove Link


}
