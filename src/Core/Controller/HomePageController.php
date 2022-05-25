<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;

/**
 * Class HomePageController
 * @package App\Core\Controller
 */
class HomePageController extends AbstractController
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
     * Public Homepage
     * 
     * @param Request $request
     * @Route("/",priority=0,  name="homepage")
     * @return Response
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
            return $this->render('@base-theme/basic-page.html.twig', [
                'page' => $page
            ]);
        }
        
        return $this->render('@base-theme/homepage.html.twig');
    }
}
