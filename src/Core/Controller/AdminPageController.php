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
use App\Core\Form\PageFormType;

/**
 * @Route("/admin/page")
 */
class AdminPageController extends AbstractController
{
    /**
     * @Route("/", name="AdminPageList")
     */
    public function index(PageRepository $pageRepo): Response
    {
        $pages = $pageRepo->findAll();

        return $this->render('@core-admin/page/page-list.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/create", name="AdminPageCreate")
    */
    public function create(
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $page = new Page();
        $form = $this->createForm(PageFormType::class, $page, [
            'submitBtn' => 'Create'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($page);
            $em->flush();
            $this->addFlash(
                'info',
                'Saved new Block with id '.$page->getId()
            );
            return $this->redirect($this->generateUrl('AdminPageShow', [
                'id' => $page->getId()
            ]));
        }
        
        return $this->render('@core-admin/page/page-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="AdminPageEdit")
     */
    public function edit(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $page = $doctrine->getRepository(Page::class);
        $page = $page->find($id);
        $form = $this->createForm(PageFormType::class, $page, [
            'submitBtn' => 'Edit'
        ]);

        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $page = $form->getData();
            $em->flush();
            $this->addFlash(
                'info',
                'Page updated'
            );
            return $this->redirect($this->generateUrl('AdminPageShow', [
                'id' => $page->getId()
            ]));
        }

        return $this->render(
            '@core-admin/page/page-edit.html.twig',
            [
                'form' => $form->createView(),
                'page' => $page
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="AdminPageShow")
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
            return $this->redirect($this->generateUrl('AdminPageList'));
        }
        
        return $this->render('@core-admin/page/page-show.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route("/delete/{id}", name="AdminPageDelete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-page', $submittedToken)) {
            $em = $doctrine->getManager();
            $page = $doctrine->getRepository(Page::class);
            $page = $page->find($id);
            if (!$page) {
                $this->addFlash(
                    'warning',
                    'There is no Page  with id ' . $id
                );
            } else {

                foreach ($page->getBlocks() as $pageBlock) {
                    $em->remove($pageBlock);
                }
                $em->remove($page);
                $em->flush();
                $this->addFlash(
                    'success',
                    'The Page with ' . $id . ' have been deleted '
                );
            } 
        } else {
            $this->addFlash(
                'warning',
                'Your CSRF token is not valid ! '
            );
        }
        
        return $this->redirect($this->generateUrl('AdminPageList'));
    }

}
