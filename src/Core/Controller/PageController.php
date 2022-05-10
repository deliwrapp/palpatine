<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;

class PageController extends AbstractController
{
    public function __construct(
        PageRepository $pageRepo,
        BlockRepository $blockRepo
    )
    {
        $this->pageRepo = $pageRepo;
        $this->blockRepo = $blockRepo;
    }

    /**
     * @Route("/{pageUrl}", priority=-1, name="page_show")
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
                return $this->redirect($this->generateUrl('homepage'));
            }
            if ($page->getRoleAccess() && !$this->isGranted($page->getRoleAccess())) {
                $this->addFlash(
                    'warning',
                    'Unauthorised Access '
                );
                return $this->redirect($this->generateUrl('homepage'));
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
            return $this->render('@core/page/basic-page.html.twig', [
                'page' => $page
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }
}
