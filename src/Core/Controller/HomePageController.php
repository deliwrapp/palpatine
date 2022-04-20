<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Page;


class HomePageController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function show(Request; $request, PageRepository $pageRepo): Response
    {
        $locale = $request->getLocale();
        $page = $this->pageRepo->findOneBy(['isHomepage' => true, 'locale' => $locale]);
        if ($page) {
            return $this->render('@core/page/basic-page.html.twig', [
                'page' => $page
            ]);
        }
        
        return $this->render('@core/page/homepage.html.twig');
    }
    
    {
        
    }
}
