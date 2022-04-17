<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Entity\Block;
use App\Core\Entity\PageBlock;
use App\Core\Repository\PageRepository;

/**
 * @Route("/page")
 */
class PageController extends AbstractController
{

    /**
     * @Route("/show/{id}", name="page_show")
     */
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $page = $doctrine->getRepository(Page::class);
        $page = $page->find($id);

        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no page  with id ' . $id
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        return $this->render('@core/page/basic-page.html.twig', [
            'page' => $page
        ]);
    }


}
