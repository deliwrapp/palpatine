<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Block;
use App\Core\Entity\PageBlock;
use App\Core\Repository\BlockRepository;
use App\Core\Form\BlockFormType;

/**
 * @Route("/admin/block")
 */
class AdminBlockController extends AbstractController
{
    /**
     * @Route("/", name="admin_block_list")
     */
    public function index(BlockRepository $blockRepo): Response
    {
        $blocks = $blockRepo->findAll();

        return $this->render('@core-admin/block/block-list.html.twig', [
            'blocks' => $blocks
        ]);
    }

    /**
     * @Route("/create", name="admin_block_create")
    */
    public function create(
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $block = new Block();
        $form = $this->createForm(BlockFormType::class, $block, [
            'submitBtn' => 'Create'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $block = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($block);
            $em->flush();
            $this->addFlash(
                'info',
                'Saved new Block with id '.$block->getId()
            );
            return $this->redirect($this->generateUrl('admin_block_show', [
                'id' => $block->getId()
            ]));
        }
        
        return $this->render('@core-admin/block/block-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_block_edit")
     */
    public function edit(
        int $id,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        $block = $doctrine->getRepository(Block::class);
        $block = $block->find($id);
        $form = $this->createForm(BlockFormType::class, $block, [
            'submitBtn' => 'Edit'
        ]);

        if (!$block) {
            $this->addFlash(
                'warning',
                'There is no Block  with id ' . $id
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $block = $form->getData();
            $em->flush();
            $this->addFlash(
                'info',
                'Block updated'
            );
            return $this->redirect($this->generateUrl('admin_block_show', [
                'id' => $block->getId()
            ]));
        }

        return $this->render(
            '@core-admin/block/block-edit.html.twig',
            [
                'form' => $form->createView(),
                'block' => $block
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="admin_block_show")
     */
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $blockContainer = $doctrine->getRepository(Block::class);
        $blockContainer = $blockContainer->find($id);

        if (!$blockContainer) {
            $this->addFlash(
                'warning',
                'There is no block  with id ' . $id
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }
        $em = $doctrine->getManager();
        $query = $em->createQuery($blockContainer->getQuery());
        $data = $query->execute();
        
        return $this->render('@core-admin/block/block-show.html.twig', [
            'blockContainer' => $blockContainer,
            'data' => $data
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_block_delete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-block', $submittedToken)) {
            $em = $doctrine->getManager();
            $block = $doctrine->getRepository(Block::class);
            $block = $block->find($id);
            if (!$block) {
                $this->addFlash(
                    'warning',
                    'There is no block  with id ' . $id
                );
            } else {
                $pageBlocks = $doctrine->getRepository(PageBlock::class);
                $pageBlocks = $pageBlocks->findBy(['block' => $id]);

                foreach ($pageBlocks as $pageBlock) {
                    $pageBlock->getPage()->removeBlock($pageBlock);
                    $em->remove($pageBlock);
                }
                $em->remove($block);
                $em->flush();
                $this->addFlash(
                    'success',
                    'The Block with ' . $id . ' have been deleted '
                );
            } 
        } else {
            $this->addFlash(
                'warning',
                'Your CSRF token is not valid ! '
            );
        }
        
        return $this->redirect($this->generateUrl('admin_block_list'));
    }

}
