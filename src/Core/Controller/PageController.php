<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;

/**
 * Class PageController
 * @package App\Core\Controller
 */
class PageController extends AbstractController
{

    /** @var PageRepository */
    private $pageRepo;

    /** @var BlockRepository */
    private $blockRepo;
    
    public function __construct(
        PageRepository $pageRepo,
        BlockRepository $blockRepo
    )
    {
        $this->pageRepo = $pageRepo;
        $this->blockRepo = $blockRepo;
    }
    
    /**
     * Page Controller
     * 
     * @param Request $request
     * @param string $pageUrl
     * @Route("/{pageUrl}", priority=-1, name="page_show")
     * @return Response
     * @return RedirectResponse
     */
    public function show(Request $request, string $pageUrl): Response
    {
        try {
            $page = $this->pageRepo->findOneBy(['url' => $pageUrl]);
            if (!$page) {
                $this->addFlash(
                    'warning',
                    'There is no page  with url ' . $pageUrl
                );
                return $this->redirect($this->generateUrl('page_error_handler', [
                    'error_code' => 404
                ]));
            }
            if ($page->getRoleAccess() && !$this->isGranted($page->getRoleAccess())) {
                $this->addFlash(
                    'warning',
                    'Unauthorised Access '
                );
                return $this->redirect($this->generateUrl('page_error_handler', [
                    'error_code' => 403
                ]));
            }
            if ($page->getBlocks()) {
                $pageBlocks = $page->getBlocks();
                foreach ($pageBlocks as $pageBlock) {
                    if ($pageBlock->getQuery()) {
                        $data = $this->blockRepo->getBlockData($pageBlock->getQuery());
                        $pageBlock->setData($data);
                    }
                }
            }
            $locale = $request->getLocale();
            if ($locale !== $page->getLocale()) {
                return $this->redirect($this->generateUrl('page_show', [
                    '_locale' => $page->getLocale(),
                    'pageUrl' => $pageUrl
                ]));
            }
            return $this->render('@base-theme/basic-page.html.twig', [
                'page' => $page
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('page_error_handler', [
                'error_code' => 501
            ]));
        }
    }
}
