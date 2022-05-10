<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Core\Entity\Block;
use App\Core\Repository\BlockRepository;
use App\Core\Form\BlockFormType;

/**
 * @Route("/admin/block")
 */
class AdminBlockController extends AbstractController
{
    public function __construct(
        BlockRepository $blockRepo
    )
    {
        $this->blockRepo = $blockRepo;
    }

    /**
     * @Route("/", name="admin_block_list")
     */
    public function index(): Response
    {
        $blocks = $this->blockRepo->findAll();

        return $this->render('@core-admin/block/block-list.html.twig', [
            'blocks' => $blocks
        ]);
    }

    /**
     * @Route("/create", name="admin_block_create")
    */
    public function create(Request $request): Response
    {
        try {
            $block = new Block();
            $form = $this->createForm(BlockFormType::class, $block, [
                'submitBtn' => 'Create'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $block = $form->getData();
                $this->blockRepo->add($block);
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
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }
    }

        // Block Duplicate
    /**
     * @Route("/duplicate/{id}", name="admin_block_duplicate")
    */
    public function duplicate(int $id): Response
    {
        try {
            $block = $this->blockRepo->find($id);

            if (!$block) {
                $this->addFlash(
                    'warning',
                    'There is no Block  with id ' . $id
                );
                return $this->redirect($this->generateUrl('admin_block_list'));
            }
            $newBlock = new Block;
            $newBlock = $block->duplicate($newBlock);
            $this->blockRepo->add($newBlock);
            $this->addFlash(
                'info',
                'Saved duplicate Block with id '.$newBlock->getId()
            );
            return $this->redirect($this->generateUrl('admin_block_edit', [
                'id' => $newBlock->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }        
    }

    /**
     * @Route("/update/{id}", name="admin_block_edit")
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $block = $this->blockRepo->find($id);
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
                $block = $form->getData();
                $this->blockRepo->flush();
                $this->addFlash(
                    'info',
                    'Block updated'
                );
                return $this->redirect($this->generateUrl('admin_block_edit', [
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
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }  
    }

    /**
     * @Route("/show/{id}", name="admin_block_show")
     */
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        try {
            $blockContainer = $this->blockRepo->find($id);

            if (!$blockContainer) {
                $this->addFlash(
                    'warning',
                    'There is no block  with id ' . $id
                );
                return $this->redirect($this->generateUrl('admin_block_list'));
            }
            $data = $blockContainer->getQuery() ?
                $this->blockRepo->getBlockData($blockContainer->getQuery(), $blockContainer->getSingleResult()) :
                null;
            $blockContainer->setData($data);
        
            return $this->render('@core-admin/block/block-show.html.twig', [
                'blockContainer' => $blockContainer,
                'data' => $data
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }  
    }

    /**
     * @Route("/delete/{id}", name="admin_block_delete")
     */
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        try {
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
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }  
        return $this->redirect($this->generateUrl('admin_block_list'));
    }

}
