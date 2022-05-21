<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Page;
use App\Core\Model\PageDuplication;
use App\Core\Form\PageFormType;
use App\Core\Form\PageDuplicationFormType;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Factory\PageFactory;

/**
 * Class EditorPageController -- Manage Page
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/page")
 */
class EditorPageController extends AbstractController
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

    /**
     * Page List Index
     * 
     * @Route("/", name="editor_page_list")
     * @return Response
     */
    public function index(): Response
    {
        try {
            $pages = $this->pageRepo->getPages();
            return $this->render('@core-admin/page/editor/page-list.html.twig', [
                'pages' => $pages
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        }
        
    }

    /**
     * Page Create
     * 
     * @param Request $request
     * @Route("/create", name="editor_page_create")
     * @return Response
     * @return RedirectResponse
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
                    return $this->redirect($this->generateUrl('editor_page_edit', [
                        'id' => $page->getId()
                    ]));
                } else {
                    $this->addFlash(
                        'warning',
                        $page
                    );
                    return $this->redirect($this->generateUrl('editor_page_create'));
                }
            }   
            return $this->render('@core-admin/page/editor/page-edit.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
        
    }

    /**
     * Page Duplicate
     * 
     * @param Request $request
     * @param int $id
     * @param string $name = null
     * @Route("/duplicate/{id}/{name}", name="editor_page_duplicate")
     * @return Response
     * @return RedirectResponse
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
                    return $this->redirect($this->generateUrl('editor_page_edit', [
                        'id' => $duplicatedPage->getId()
                    ]));
                } else {
                    $this->addFlash(
                        'warning',
                        'Duplication not possible - ' .$duplicatedPage
                    );
                    return $this->redirect($this->generateUrl('editor_page_duplicate', [
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
            return $this->render('@core-admin/page/editor/page-duplication.html.twig', [
                'form' => $form->createView(),
                'page' => $page
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
        
    }

    /**
     * Page Edit 
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}",  name="editor_page_edit")
     * @return Response
     * @return RedirectResponse
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
                'action' => $this->generateUrl('editor_page_edit_locale', [
                    'id' => $page->getId()
                ]),
                'method' => 'POST',
            ]);
            $formUrl = $this->createForm(PageFormType::class, $page, [
                'mode' => 'edit-url',
                'action' => $this->generateUrl('editor_page_edit_url', [
                    'id' => $page->getId()
                ]),
                'method' => 'POST',
            ]);
            $formAddToPageGroup = $this->createForm(PageFormType::class, $page, [
                'mode' => 'add-page-to-page-group',
                'action' => $this->generateUrl('editor_page_add_to_page_group', [
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
                return $this->redirect($this->generateUrl('editor_page_edit', [
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
                '@core-admin/page/editor/page-edit.html.twig',
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
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }

    /**
     * Page Edit Url
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/url/{id}", name="editor_page_edit_url")
     * @return RedirectResponse
     */
    public function editUrl(int $id, Request $request): RedirectResponse
    {
        try {
            $page = $this->pageVerificator($id);
            $formUrl = $this->createForm(PageFormType::class, $page, [
                'mode' => 'edit-url',
                'action' => $this->generateUrl('editor_page_edit_url', [
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
            return $this->redirect($this->generateUrl('editor_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }
 
    /**
     * Page Change Locale
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/locale/{id}", name="editor_page_edit_locale")
     * @return RedirectResponse
     */
    public function editLocale(int $id, Request $request): RedirectResponse
    {
        try {
            $page = $this->pageVerificator($id);
            $formLocale = $this->createForm(PageFormType::class, $page, [
                'mode' => 'edit-locale',
                'action' => $this->generateUrl('editor_page_edit_locale', [
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
            return $this->redirect($this->generateUrl('editor_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }

    /**
     * Page Add other page to page pageGroup
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/page-group/{id}", name="editor_page_add_to_page_group")
     * @return RedirectResponse
     */
    public function addPageToPageGroup(int $id, Request $request): RedirectResponse
    {
        try {
            $page = $this->pageVerificator($id);
            $formAddToPageGroup = $this->createForm(PageFormType::class, $page, [
                'mode' => 'add-page-to-page-group',
                'action' => $this->generateUrl('editor_page_add_to_page_group', [
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
            return $this->redirect($this->generateUrl('editor_page_edit', [
                'id' => $page->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }

    /**
     * Page Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="editor_page_show")
     * @return Response
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
            return $this->render('@core-admin/page/editor/page-show.html.twig', [
                'page' => $page
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
    }

    /**
     * Page Delete
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="editor_page_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
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
        return $this->redirect($this->generateUrl('editor_page_list'));
    }

    /**
     * Test if page exists and return it, or redirect to page list index with an error message
     * 
     * @param int $pageId
     * @return Page $page
     * @return RedirectResponse
     */
    public function pageVerificator(int $pageId)
    {
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $pageId
            );
            return $this->redirect($this->generateUrl('editor_page_list'));
        }
        return $page;
    }
}
