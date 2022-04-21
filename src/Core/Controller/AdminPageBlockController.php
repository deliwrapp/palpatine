<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\PageBlock;
use App\Core\Repository\PageRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Repository\PageBlockRepository;
use App\Core\Services\PageVerificator;

/**
 * @Route("/admin/page")
 */
class AdminPageBlockController  extends AbstractController
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var PageBlockRepository */
    private $pageBlockRepo;

    /** @var BlockRepository */
    private $blockRepo;

    /** @var PageVerificator */
    private $pageVerif;

    /** @var ManagerRegistry */
    private $em;

    public function __construct(
        PageRepository $pageRepo,
        PageBlockRepository $pageBlockRepo,
        BlockRepository $blockRepo,
        PageVerificator $pageVerif,
        ManagerRegistry $em
    )
    {
        $this->pageRepo= $pageRepo;
        $this->pageBlockRepo= $pageBlockRepo;
        $this->blockRepo= $blockRepo;
        $this->pageVerif= $pageVerif;
        $this->em = $em->getManager();
    }

    // Page Add Block to Page
    /**
     * @Route("/block/{blockId}/add-to/{pageId}", name="admin_page_block_add")
     */
    public function addBlockToPage(int $blockId, int $pageId): Response
    {
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $pageId
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
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
        $pageBlock = new PageBlock;
        $pageBlock->setBlock($block);
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
        $page = $this->pageRepo->find($pageId);
        if (!$page) {
            $this->addFlash(
                'warning',
                'There is no Page  with id ' . $pageId
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
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
        if ($page !== $pageBlock->getPage()) {
            $this->addFlash(
                'warning',
                'Block and Page are not linked '
            );
            return $this->redirect($this->generateUrl('admin_page_edit', [
                'id' => $pageId
            ]));
        }
        $this->pageBlockRepo->remove($pageBlock);
        $this->addFlash(
            'success',
            'The Page Block with ' . $pageBlockId . ' have been deleted '
        );
        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $pageId
        ]));
    }

    // PageBlock change position
    /**
     * @Route("/block/{pâgeBlockId}/move-to/{direction}", name="admin_page_block_position")
     */
    public function moveBlockTo(
        int $pâgeBlockId,
        string $direction,
        PageBlockRepository $pageBlockRepo
    ): Response
    {
        $pageBlock = $this->pageBlockRepo->find($pâgeBlockId);
        if (!$pageBlock) {
            $this->addFlash(
                'warning',
                'There is no Block  with id ' . $pâgeBlockId
            );
            return $this->redirect($this->generateUrl('admin_page_list'));
        }
        $actualPosition = $pageBlock->getPosition();
        $page = $pageBlock->getPage();
        if ($direction == 'up') {
            $pageBlockToSwitch = $this->pageBlockRepo->findOneBy([
                'page_id' => $page->getId(),
                'position' => $actualPosition - 1
            ]);
            $pageBlockToSwitch->setPosition($actualPosition);
            $pageBlock->setPosition($actualPosition - 1);
        } else {
            $pageBlockToSwitch = $this->pageBlockRepo->findOneBy([
                'page_id' => $page->getId(),
                'position' => $actualPosition + 1
            ]);
            $pageBlockToSwitch->setPosition($actualPosition);
            $pageBlock->setPosition($actualPosition + 1);
        }
        $this->pageBlockRepo->flush();

        return $this->redirect($this->generateUrl('admin_page_edit', [
            'id' => $pageBlock->getPage()->getId()
        ]));
    }

}
