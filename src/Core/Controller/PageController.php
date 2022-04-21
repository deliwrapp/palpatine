<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;

class PageController extends AbstractController
{
    /**
     * @Route("/{pageUrl}", priority=-1, name="page_show")
     */
    public function show(Request $request, PageRepository $pageRepo, string $pageUrl): Response
    {
        $page = $pageRepo->findOneBY(['url' => $pageUrl]);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no page  with url ' . $pageUrl
            );
            return $this->redirect($this->generateUrl('homepage'));
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
