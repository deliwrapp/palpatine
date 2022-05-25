<?php

namespace App\Core\Controller\Moderator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;

/**
 * Class ModeratorPageController -- Moderate Page
 * @package App\Core\Controller\Moderator
 * @IsGranted("ROLE_MODERATOR",statusCode=401, message="No access! Get out!")
 * @Route("/moderator/page")
 */
class ModeratorPageController extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    public function __construct(
        PageRepository $pageRepo
    )
    {
        $this->pageRepo = $pageRepo;
    }

    /**
     * Page List Index
     * 
     * @Route("/", name="moderator_page_list")
     * @return Response
     */
    public function index(): Response
    {
        try {
            $pages = $this->pageRepo->getPages();
            return $this->render('@core-admin/page/moderator/page-list.html.twig', [
                'pages' => $pages
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        }
        
    }


    /**
     * Page Edit 
     * 
     * @param int $id
     * @Route("/publish/{id}",  name="moderator_page_publish")
     * @return Response
     * @return RedirectResponse
     */
    public function publish(int $id): Response
    {
        try {
            $page = $this->pageVerificator($id);
            $page->setIsPublished(true);
            $this->pageRepo->add($page);
            $this->addFlash(
                'info',
                'Page Published'
            );
            return $this->redirect($this->generateUrl('moderator_page_list'));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('moderator_page_list'));
        }
    }

    /**
     * Page Edit 
     * 
     * @param int $id
     * @Route("/unpublish/{id}",  name="moderator_page_unpublish")
     * @return Response
     * @return RedirectResponse
     */
    public function unpublish(int $id): Response
    {
        try {
            $page = $this->pageVerificator($id);
            
            $page->setIsPublished(false);
            $this->pageRepo->add($page);
            $this->addFlash(
                'info',
                'Page Unpublished'
            );
            return $this->redirect($this->generateUrl('moderator_page_list'));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('moderator_page_list'));
        }
    }

    /**
     * Page Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="moderator_page_show")
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $page = $this->pageVerificator($id);
            if ($page->getBlocks()) {
                $pageBlocks = $page->getBlocks();
                foreach ($pageBlocks as $pageBlock) {
                    if ($pageBlock->getQuery()) {
                        $data = $this->blockRepo->getBlockData($pageBlock->getQuery());
                        $pageBlock->setData($data);
                    }
                }
            } 
            return $this->render('@core-admin/page/moderator/page-show.html.twig', [
                'page' => $page
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }

    /**
     * Test if page exists and return it, or redirect to page list index with an error message
     * 
     * @param int $pageId
     * @return Page $page
     * @return RedirectResponse
     */
    public function pageVerificator(int $pageId)
    {
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $pageId
            );
            return $this->redirect($this->generateUrl('moderator_page_list'));
        }
        return $page;
    }
}
