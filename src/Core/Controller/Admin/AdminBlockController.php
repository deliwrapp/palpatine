<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Block;
use App\Core\Repository\BlockRepository;
use App\Core\Form\BlockFormType;

/**
 * Class AdminBlockController
 * @package App\Core\Controller\Admin
 * @Route("/admin/block")
 */
class AdminBlockController extends AbstractController
{
 
    /** @var BlockRepository */
    private $blockRepo;

    public function __construct(
        BlockRepository $blockRepo
    )
    {
        $this->blockRepo = $blockRepo;
    }

    /**
     * Block Lists Index
     * 
     * @Route("/", name="admin_block_list")
     * @return Response
     */
    public function index(): Response
    {
        $blocks = $this->blockRepo->findAll();
        return $this->render('@core-admin/block/block-list.html.twig', [
            'blocks' => $blocks
        ]);
    }

    /**
     * Block Create
     * 
     * @param Request $request 
     * @Route("/create", name="admin_block_create") 
     * @return Response 
     * @return RedirectResponse
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

    /**
     * Block Duplicate
     * 
     * @param int $id 
     * @Route("/duplicate/{id}", name="admin_block_duplicate") 
     * @return RedirectResponse
    */
    public function duplicate(int $id): RedirectResponse
    {
        try {
            $block = $this->blockVerificator($id);
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
     * Block Edit
     * 
     * @param int $id
     * @param Request $request 
     * @Route("/update/{id}", name="admin_block_edit") 
     * @return Response 
     * @return RedirectResponse
     */
    public function edit(int $id, Request $request): Response
    {
        try {
            $block = $this->blockVerificator($id);
            $form = $this->createForm(BlockFormType::class, $block, [
                'submitBtn' => 'Edit'
            ]);            
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
     * Block Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="admin_block_show") 
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $blockContainer = $this->blockVerificator($id);
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
     * Block Delete
     * 
     * @param int $id
     * @param Request $request 
     * @Route("/delete/{id}", name="admin_block_delete") 
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('delete-block', $submittedToken)) {
                $block = $this->blockVerificator($id);
                $this->blockRepo->remove($block);
                $this->blockRepo->flush();
                $this->addFlash(
                    'success',
                    'The Block with ' . $id . ' have been deleted '
                );
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

    /**
     * Test if block exists and return it, or redirect to menu list index with an error message
     * 
     * @param int $blockId
     * @return Block
     * @return RedirectResponse
     */
    public function blockVerificator(int $blockId)
    {
        $block = $this->blockRepo->find($blockId);
        if (!$block) {
            $this->addFlash(
                'warning',
                'There is no Block  with id ' . $blockId
            );
            return $this->redirect($this->generateUrl('admin_block_list'));
        }
        return $block;
    }

}
