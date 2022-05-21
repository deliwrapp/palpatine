<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use App\Core\Verificator\PageVerificator;

/**
 * Class EditorSubPageController -- Manage Subpage
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/page")
 */
class EditorSubPageController extends AbstractController
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
     * @Route("/add-subpage-to/{id}", name="editor_subpage_add")
     * @return Response
     * @return RedirectResponse
    */
    public function addSubpage(int $id, PageRepository $pageRepo): Response
    {
        try {
            $page = $this->pageRepo->find($id);
            return $this->render('@core-admin/page/editor/page-show.html.twig', [
                'page' => $page
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        }  
    }


    // Page Remove SubPage


    // Page Link As a Subpage


    // SubPage Remove Link


}
