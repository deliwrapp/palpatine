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
        $pages = $pageRepo->getPages();

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
            'submitBtn' => 'Create',
            'mode' => 'creation'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $url = $form->get('name')->getData();
            $page->setUrl($url);
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
        $page = $doctrine->getRepository(Page::class)->find($id);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }

        $form = $this->createForm(PageFormType::class, $page, [
            'submitBtn' => 'Edit'
        ]);
        $formLocale = $this->createForm(PageFormType::class, $page, [
            'mode' => 'edit-locale',
            'action' => $this->generateUrl('admin_page_edit_locale', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        $formUrl = $this->createForm(PageFormType::class, $page, [
            'mode' => 'edit-url',
            'action' => $this->generateUrl('admin_page_edit_url', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        $formAddToPageGroup = $this->createForm(PageFormType::class, $page, [
            'mode' => 'add-page-to-page-group',
            'action' => $this->generateUrl('admin_page_add_to_page_group', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        $formSendToPageGroup = $this->createForm(PageFormType::class, $page, [
            'mode' => 'send-page-to-page-group',
            'action' => $this->generateUrl('admin_page_send_to_page_group', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        
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
                'formLocale' => $formLocale->createView(),
                'formUrl' => $formUrl->createView(),
                'formAddToPageGroup' => $formAddToPageGroup->createView(),
                'formSendToPageGroup' => $formSendToPageGroup->createView(),
                'page' => $page
            ]
        );
    }

    // Page Edit Url
    /**
     * @Route("/update/url/{id}", name="admin_page_edit_url")
     */
    public function editUrl(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $pageRepo = $doctrine->getRepository(Page::class);
        $page = $pageRepo->find($id);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }

        $formUrl = $this->createForm(PageFormType::class, $page, [
            'mode' => 'edit-url',
            'action' => $this->generateUrl('admin_page_edit_url', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        
        $formUrl->handleRequest($request);

        if ($formUrl->isSubmitted() && $formUrl->isValid()) {
            $em = $doctrine->getManager();
            $page = $formUrl->getData();
            $em->flush();
            $this->addFlash(
                'info',
                'Page Url updated'
            );
        }

        return $this->redirect($this->generateUrl('AdminPageEdit', [
            'id' => $page->getId()
        ]));

    }
 
    // Page Change Locale
    /**
     * @Route("/update/locale/{id}", name="admin_page_edit_locale")
     */
    public function editLocale(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $pageRepo = $doctrine->getRepository(Page::class);
        $page = $pageRepo->find($id);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }

        $formLocale = $this->createForm(PageFormType::class, $page, [
            'mode' => 'edit-locale',
            'action' => $this->generateUrl('admin_page_edit_locale', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);
        $formLocale->handleRequest($request);

        if ($formLocale->isSubmitted() && $formLocale->isValid()) {

            $newLocale = $formLocale->get('locale')->getData();
            $pageGroupId = $page->getPageGroupId();
            $pageTest = $pageRepo->findOneByPageGroupAndLocale($pageGroupId, $newLocale);

            if ($pageTest) {
                $this->addFlash(
                    'warning',
                    'An Page with this Locale already exists !'
                );
            } else {
                $em = $doctrine->getManager();
                $page->setLocale($newLocale);
                $em->flush();
                $this->addFlash(
                    'info',
                    'Page Locale updated'
                );
            }
        }
        return $this->redirect($this->generateUrl('AdminPageEdit', [
            'id' => $page->getId()
        ]));
    }

    // Page Add other page to page pageGroup
    /**
     * @Route("/update/page-group/{id}", name="admin_page_add_to_page_group")
     */
    public function addPageToPageGroup(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $pageRepo = $doctrine->getRepository(Page::class);
        $page = $pageRepo->find($id);

        $formAddToPageGroup = $this->createForm(PageFormType::class, $page, [
            'mode' => 'add-page-to-page-group',
            'action' => $this->generateUrl('admin_page_add_to_page_group', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);

        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }
        
        $formAddToPageGroup->handleRequest($request);

        if ($formAddToPageGroup->isSubmitted() && $formAddToPageGroup->isValid()) {
            $em = $doctrine->getManager();
            /* $page = $formAddToPageGroup->getData();
            $em->flush(); */
            $this->addFlash(
                'info',
                'Page Group updated'
            );
        }

        return $this->redirect($this->generateUrl('AdminPageEdit', [
            'id' => $page->getId()
        ]));

    }

    // Page Send page to other pageGroup
    /**
     * @Route("/update/page-group/{id}", name="admin_page_send_to_page_group")
     */
    public function sendPageToPageGroup(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $pageRepo = $doctrine->getRepository(Page::class);
        $page = $pageRepo->find($id);

        $formSendToPageGroup = $this->createForm(PageFormType::class, $page, [
            'mode' => 'send-page-to-page-group',
            'action' => $this->generateUrl('admin_page_send_to_page_group', [
                'id' => $page->getId()
            ]),
            'method' => 'POST',
        ]);

        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page with id ' . $id
            );
            return $this->redirect($this->generateUrl('AdminPageList'));
        }
        
        $formSendToPageGroup->handleRequest($request);

        if ($formSendToPageGroup->isSubmitted() && $formSendToPageGroup->isValid()) {
            $em = $doctrine->getManager();
            /* $page = $formAddToPageGroup->getData();
            $em->flush(); */
            $this->addFlash(
                'info',
                'Page Group updated'
            );
        }

        return $this->redirect($this->generateUrl('AdminPageEdit', [
            'id' => $page->getId()
        ]));

    }



    // Page Add SubPage


    // Page Remove SubPage


    // Page Link As a Subpage


    // SubPage Remove Link


    // Page Duplicate To an Other Language

    

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
