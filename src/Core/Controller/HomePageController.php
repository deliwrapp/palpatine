<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;


class HomePageController extends AbstractController
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
     * @Route("/", name="homepage")
     */
    public function show(Request $request): Response
    {
        $locale = $request->getLocale();
        $page = $this->pageRepo->findOneBy(['isHomepage' => true,'isPublished' => true, 'locale' => $locale]);
        if ($page) {
            if ($page->getBlocks()) {
                $pageBlocks = $page->getBlocks();
                foreach ($pageBlocks as $pageBlock) {
                    if ($pageBlock->getQuery()) {
                        $data = $this->blockRepo->getBlockData($pageBlock->getQuery());
                        $pageBlock->setData($data);
                    }
                }
            }
            return $this->render('@core/page/basic-page.html.twig', [
                'page' => $page
            ]);
        }
        
        return $this->render('@core/page/homepage.html.twig');
    }
}
