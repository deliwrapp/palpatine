<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Page;
use App\Core\FormObject\PageDuplication;
use App\Core\Form\PageFormType;
use App\Core\Form\PageDuplicationFormType;
use App\Core\Repository\PageRepository;
use App\Core\Services\PageFactory;
use App\Core\Repository\BlockRepository;

/**
 * @Route("/admin/page")
 */
class AdminPageController extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var BlockRepository */
    private $blockRepo;

    /** @var PageFactory */
    private $pageFactory;

    public function __construct(
        PageRepository $pageRepo,
        BlockRepository $blockRepo,
        PageFactory $pageFactory
    )
    {
        $this->pageRepo = $pageRepo;
        $this->blockRepo = $blockRepo;
        $this->pageFactory = $pageFactory;
    }

    // Page List
    /**
     * @Route("/", name="admin_page_list")
     */
    public function index(): Response
    {
        $pages = $this->pageRepo->getPages();
        return $this->render('@core-admin/page/page-list.html.twig', [
            'pages' => $pages
        ]);
    }

    // Page Create
    /**
     * @Route("/create", name="admin_page_create")
    */
    public function create(Request $request): Response
    {
        try {
            $page = new Page();
            $form = $this->createForm(PageFormType::class, $page, [
                'submitBtn' => 'Create',
                'mode' => 'creation'
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $page = $this->pageFactory->initPage($form->getData(), true);
                if ($page instanceof Page) {
                    $this->addFlash(
                        'info',
                        'Saved new Page with id '.$page->getId()
                    );
                    return $this->redirect($this->generateUrl('admin_page_edit', [
                        'id' => $page->getId()
                    ]));
                } else {
                    $this->addFlash(
                        'warning',
                        $page
                    );
                    return $this->redirect($this->generateUrl('admin_page_create'));
                }
            }   
            return $this->render('@core-admin/page/page-edit.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
        
    }

    // Page Duplicate
    /**
     * @Route("/duplicate/{id}/{name}", name="admin_page_duplicate")
    */
    public function duplicate(Request $request, int $id, string $name = null): Response
    {
        try {
            $page = $this->pageVerificator($id);
            $pageDuplication = new PageDuplication();
            if ($name) {
                $pageDuplication->setName($name);
            }
            $form = $this->createForm(PageDuplicationFormType::class, $pageDuplication);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $name = $form->get('name')->getData();
                $locale = $form->get('locale')->getData();
                $duplicatedPage = $this->pageFactory->duplicatePage($page, $name, $locale, true);
                if ($duplicatedPage instanceof Page) {
                    $this->addFlash(
                        'info',
                        'Saved new duplicated Page with id '.$duplicatedPage->getId()
                    );
                    return $this->redirect($this->generateUrl('admin_page_edit', [
                        'id' => $duplicatedPage->getId()
                    ]));
                } else {
                    $this->addFlash(
                        'warning',
                        'Duplication not possible - ' .$duplicatedPage
                    );
                    return $this->redirect($this->generateUrl('admin_page_duplicate', [
                        'id' => $page->getId(),
                        'name' => $name
                    ]));
                }
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
            return $this->render('@core-admin/page/page-duplication.html.twig', [
                'form' => $form->createView(),
                'page' => $page
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
        
    }

    // Page Edit 
    /**
     * @Route("/update/{id}",  name="admin_page_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $page = $this->pageVerificator($id);
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
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $page = $form->getData();
                $this->pageRepo->add($page);
                $this->addFlash(
                    'info',
                    'Page updated'
                );
                return $this->redirect($this->generateUrl('admin_page_edit', [
                    'id' => $page->getId()
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
            $blocks = $this->blockRepo->findBy(['isPublished' => true]);
            return $this->render(
                '@core-admin/page/page-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'formLocale' => $formLocale->createView(),
                    'formUrl' => $formUrl->createView(),
                    'formAddToPageGroup' => $formAddToPageGroup->createView(),
                    'page' => $page,
                    'blocks' => $blocks
                ]
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
        
    }

    // Page Edit Url
    /**
     * @Route("/update/url/{id}", name="admin_page_edit_url")
     */
    public function editUrl(int $id, Request $request): Response
    {
        try {
            $page = $this->pageVerificator($id);
            $formUrl = $this->createForm(PageFormType::class, $page, [
                'mode' => 'edit-url',
                'action' => $this->generateUrl('admin_page_edit_url', [
                    'id' => $page->getId()
                ]),
                'method' => 'POST',
            ]);
            $formUrl->handleRequest($request);
            if ($formUrl->isSubmitted() && $formUrl->isValid()) {
                $url = $formUrl->get('url')->getData();
                $url = $this->pageFactory->urlConverter($url);
                $page->setUrl($url);
                $this->pageRepo->flush();
                $this->addFlash(
                    'info',
                    'Page Url updated'
                );
            }
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
    }
 
    // Page Change Locale
    /**
     * @Route("/update/locale/{id}", name="admin_page_edit_locale")
     */
    public function editLocale(int $id, Request $request): Response
    {
        try {
            $page = $this->pageVerificator($id);
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
                $pageTest = $this->pageRepo->findOneByPageGroupAndLocale($pageGroupId, $newLocale);
                if ($pageTest) {
                    $this->addFlash(
                        'warning',
                        'An Page with this Locale already exists !'
                    );
                } else {
                    $page->setLocale($newLocale);
                    $this->pageRepo->flush();
                    $this->addFlash(
                        'info',
                        'Page Locale updated'
                    );
                }
            }
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
    }

    // Page Add other page to page pageGroup
    /**
     * @Route("/update/page-group/{id}", name="admin_page_add_to_page_group")
     */
    public function addPageToPageGroup(int $id, Request $request): Response
    {
        try {
            $page = $this->pageVerificator($id);
            $formAddToPageGroup = $this->createForm(PageFormType::class, $page, [
                'mode' => 'add-page-to-page-group',
                'action' => $this->generateUrl('admin_page_add_to_page_group', [
                    'id' => $page->getId()
                ]),
                'method' => 'POST',
            ]);
            $formAddToPageGroup->handleRequest($request);
            if ($formAddToPageGroup->isSubmitted() && $formAddToPageGroup->isValid()) {
                
                /* $page = $formAddToPageGroup->getData();
                $this->pageRepo->flush(); */
                $this->addFlash(
                    'info',
                    'Page Group updated'
                );
            }
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
    }

    /**
     * @Route("/show/{id}", name="admin_page_show")
     */
    public function show(int $id): Response
    {
        try {
            $page = $this->pageVerificator($id);
            if ($page->getBlocks()) {
                $pageBlocks = $page->getBlocks();
                foreach ($pageBlocks as $pageBlock) {
                    if ($pageBlock->getQuery()) {
                        $data = $this->blockRepo->getBlockData($pageBlock->getQuery());
                        $pageBlock->setData($data);
                    }
                }
            } 
            return $this->render('@core-admin/page/page-show.html.twig', [
                'page' => $page
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
    }

    /**
     * @Route("/delete/{id}", name="admin_page_delete")
     */
    public function delete(int $id, Request $request): Response
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-page', $submittedToken)) {
                $page = $this->pageVerificator($id);
                $this->pageRepo->remove($page);
                $this->addFlash(
                    'success',
                    'The Page with ' . $id . ' have been deleted '
                ); 
            } else {
                $this->addFlash(
                    'warning',
                    'Your CSRF token is not valid ! '
                );
            } 
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_page_list'));
    }

    public function pageVerificator(int $pageId)
    {
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $pageId
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
        return $page;
    }
}
