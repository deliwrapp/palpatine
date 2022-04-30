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
        $page = $this->pageRepo->findOneBy(['url' => $pageUrl]);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no page  with url ' . $pageUrl
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
        if ($page->getBlocks()) {
            $pageBlocks = $page->getBlocks();
            foreach ($pageBlocks as $pageBlock) {
                if ($pageBlock->getBlock()) {
                    $data = $this->blockRepo->getBlockData($pageBlock->getBlock()->getQuery());
                    $pageBlock->getBlock()->setData($data);
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
    }
}
