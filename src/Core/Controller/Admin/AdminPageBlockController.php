<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Page;
use App\Core\Entity\PageBlock;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Repository\PageBlockRepository;
use App\Core\Services\PageFactory;
use App\Core\Form\PageBlockFormType;

/**
 * @Route("/admin/page")
 */
class AdminPageBlockController  extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var PageBlockRepository */
    private $pageBlockRepo;
    
    /** @var PageFactory */
    private $pageFactory;

    public function __construct(
        PageRepository $pageRepo,
        PageBlockRepository $pageBlockRepo,
        PageFactory $pageFactory
    )
    {
        $this->pageRepo = $pageRepo;
        $this->pageBlockRepo = $pageBlockRepo;
        $this->pageFactory = $pageFactory;
    }

    // Page Add Block to Page
    /**
     * @Route("/block/{blockId}/add-to/{pageId}", name="admin_page_block_add")
     */
    public function addBlockToPage($blockId, int $pageId): Response
    {
        $page =$this->pageVerificator($pageId);
        $pageBlock = new PageBlock;
        if (is_int($blockId)) {
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
            $pageBlock->setBlock($block);
        }
        $pageBlockPos = count($page->getBlocks()) + 1;
        $pageBlock->setPosition($pageBlockPos);
        $page->addBlock($pageBlock);
        $this->pageBlockRepo->add($pageBlock);
        $this->addFlash(
            'success',
            'The Page Block with have been added'
        );
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
        $page =$this->pageVerificator($pageId);
        $pageBlock = $this->pageBlockVerificator($pageBlockId, $pageId);
        $this->pageBlockLinkVerificator($pageBlock, $page);
        $this->pageBlockRepo->remove($pageBlock);
        $this->addFlash(
            'success',
            'The Page Block with ' . $pageBlockId . ' have been deleted '
        );
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
        $page =$this->pageVerificator($pageId);
        $page = $this->pageFactory->reOrderPageBlock($page);
        
        $this->addFlash(
            'success',
            'The Page Blocks have been reordered'
        );

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
    }
}
