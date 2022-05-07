<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Page;
use App\Core\Entity\PageBlock;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Repository\PageBlockRepository;
use App\Core\Services\PageFactory;
use App\Core\Services\TemplateFactory;
use App\Core\Form\PageBlockFormType;

/**
 * @Route("/admin/page")
 */
class AdminPageBlockController  extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var BlockRepository */
    private $blockRepo;

    /** @var PageBlockRepository */
    private $pageBlockRepo;
    
    /** @var PageFactory */
    private $pageFactory;

    /** @var TemplateFactory */
    private $tplFactory;

    public function __construct(
        PageRepository $pageRepo,
        BlockRepository $blockRepo,
        PageBlockRepository $pageBlockRepo,
        PageFactory $pageFactory,
        TemplateFactory $tplFactory
    )
    {
        $this->pageRepo = $pageRepo;
        $this->blockRepo = $blockRepo;
        $this->pageBlockRepo = $pageBlockRepo;
        $this->pageFactory = $pageFactory;
        $this->tplFactory = $tplFactory;
    }

    // Page Add Block to Page
    /**
     * @Route("/add-block-to-page/{pageId}/{option}/{blockId}", 
     * name="admin_page_block_add",
     * defaults = {"option" = "new", "blockId" = null}
     * )
     */
    public function addBlockToPage(int $pageId, string $option = 'new', int $blockId = null): Response
    {
        try {
            $page =$this->pageVerificator($pageId);
            $pageBlock = new PageBlock;
            if ($option == 'duplicate' && $blockId) {
                $block = $this->blockRepo->find($blockId);
                if (!$block) {
                    $this->addFlash(
                        'warning',
                        'There is no Block  with id ' . $blockId
                    );
                    return $this->redirect($this->generateUrl('admin_page_edit', [
                        'id' => $pageId
                    ]));
                }
                $pageBlock = $block->copyToPageBlock($pageBlock);
            } else {
                $tpl = $this->tplFactory->checkIfDefaultTemplateExists() ? 
                            $this->tplFactory->checkIfDefaultTemplateExists() :
                            $this->tplFactory->createDefaultTemplate()
            ;
                $pageBlock->setBlockTemplate($tpl);
            }
            $pageBlockPos = count($page->getBlocks()) + 1;
            $pageBlock->setPosition($pageBlockPos);
            $pageBlock->setName('block-'.$pageBlockPos);
            $page->addBlock($pageBlock);
            $this->pageBlockRepo->add($pageBlock);
            $this->addFlash(
                'success',
                'The Page Block with have been added'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        } 
        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $pageId
        ]));
    }

    // Page Duplicate PageBlock from Page
    /**
     * @Route("/duplicate-block/{pageBlockId}/from/{pageId}", name="admin_page_block_duplicate")
     */
    public function duplicateBlockFromPage(int $pageBlockId, int $pageId): Response
    {
        try {
            $page =$this->pageVerificator($pageId);
            $pageBlock = $this->pageBlockVerificator($pageBlockId, $pageId);
            $this->pageBlockLinkVerificator($pageBlock, $page);
            $duplicatePageBlock = new PageBlock;
            $duplicatePageBlock = $pageBlock->duplicate($duplicatePageBlock);
            $pageBlockPos = count($page->getBlocks()) + 1;
            $duplicatePageBlock->setPosition($pageBlockPos);
            $page->addBlock($duplicatePageBlock );
            $this->pageBlockRepo->add($duplicatePageBlock);
            $this->addFlash(
                'success',
                'The Page Block have been duplicated'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $pageId
        ]));
    }

    // Page Remove Block from Page
    /**
     * @Route("/remove-block/{pageBlockId}/from/{pageId}", name="admin_page_block_remove")
     */
    public function removeBlockFromPage(int $pageBlockId, int $pageId): Response
    {
        try {
            $page =$this->pageVerificator($pageId);
            $pageBlock = $this->pageBlockVerificator($pageBlockId, $pageId);
            $this->pageBlockLinkVerificator($pageBlock, $page);
            $this->pageBlockRepo->remove($pageBlock);
            $this->addFlash(
                'success',
                'The Page Block with ' . $pageBlockId . ' have been deleted '
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_page_block_reorder', [
            'pageId' => $pageId
        ]));
    }

    // PageBlock change position
    /**
     * @Route("/block/{pageBlockId}/page/{pageId}/move-to/{position}", name="admin_page_block_position")
     */
    public function moveBlockTo(int $pageBlockId, int $pageId, int $position): Response
    {
        try {
            $page = $this->pageVerificator($pageId);
            $pageBlock = $this->pageBlockVerificator($pageBlockId, $pageId);
            $this->pageBlockLinkVerificator($pageBlock, $page);
            
            if ($position == 0 || $position > count($page->getBlocks())) {
                $this->addFlash(
                    'warning',
                    'You can not move block out !'
                );
                return $this->redirect($this->generateUrl('admin_page_edit', [
                    'id' => $page->getId()
                ]));
            }

            $pageBlockToSwitch = $this->pageBlockRepo->findOneBy([
                'page' => $page->getId(),
                'position' => $position
            ]);
            $actualPosition = $pageBlock->getPosition();
            $pageBlockToSwitch->setPosition($actualPosition);
            $pageBlock->setPosition($position);
            
            $this->pageBlockRepo->flush();
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $page->getId()
        ]));
    }

    // Page Reorder Blocks on the Page
    /**
     * @Route("/block/re-order/{pageId}", name="admin_page_block_reorder")
     */
    public function reOrderBlocksOnPage(int $pageId): Response
    {
        try {
            $page =$this->pageVerificator($pageId);
            $page = $this->pageFactory->reOrderPageBlock($page);
            
            $this->addFlash(
                'success',
                'The Page Blocks have been reordered'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $pageId
        ]));
    }

        // Page Block Edit 
    /**
     * @Route("/page-block/editor/{pageBlockId}/page/{pageId}", name="admin_page_block_edit")
     */
    public function edit(int $pageBlockId, int $pageId, Request $request): Response
    {
        try {
            $page =$this->pageVerificator($pageId);
            $pageBlock = $this->pageBlockVerificator($pageBlockId, $pageId);
            $this->pageBlockLinkVerificator($pageBlock, $page);

            $form = $this->createForm(PageBlockFormType::class, $pageBlock, ['method' => 'POST']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->pageBlockRepo->flush();
                $this->addFlash(
                    'info',
                    'Page Block Content updated'
                );
                return $this->redirect($this->generateUrl('admin_page_block_edit', [
                    'pageBlockId' => $pageBlockId,
                    'pageId' => $pageId
                ]));
            }

            return $this->render(
                '@core-admin/page-block/page-block-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'pageBlock' => $pageBlock
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
    
    public function pageBlockVerificator(int $pageBlockId, int $pageId)
    {
        $pageBlock = $this->pageBlockRepo->find($pageBlockId);
        if (!$pageBlock) {
            $this->addFlash(
                'warning',
                'There is no Block  with id ' . $pageBlockId
            );
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $pageId
            ]));
        }
        return $pageBlock;
    }

    public function pageBlockLinkVerificator(PageBlock $pageBlock, Page $page)
    {
        if ($page !== $pageBlock->getPage()) {
            $this->addFlash(
                'warning',
                'Block and Page are not linked '
            );
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $page->getId()
            ]));
        }
        return true;
    }
}
