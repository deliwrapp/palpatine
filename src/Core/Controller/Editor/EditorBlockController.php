<?php

namespace App\Core\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Block;
use App\Core\Entity\File;
use App\Core\Repository\BlockRepository;
use App\Core\Form\BlockFormType;
use App\Core\Repository\FileRepository;
use App\Core\Factory\BlockFactory;

/**
 * Class EditorBlockController
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/block")
 */
class EditorBlockController extends AbstractController
{
 
    /** @var BlockRepository */
    private $blockRepo;

    /** @var BlockFactory */
    private $blockFactory;

    /** @var FileRepository */
    private $fileRepo;

    public function __construct(
        BlockRepository $blockRepo,
        BlockFactory $blockFactory,
        FileRepository $fileRepo
    )
    {
        $this->blockRepo = $blockRepo;
        $this->blockFactory = $blockFactory;
        $this->fileRepo = $fileRepo;
    }

    /**
     * Block Lists Index
     * 
     * @Route("/", name="editor_block_list")
     * @return Response
     */
    public function index(): Response
    {
        $blocks = $this->blockRepo->findAll();
        return $this->render('@core-admin/block/editor/block-list.html.twig', [
            'blocks' => $blocks
        ]);
    }

    /**
     * Block Create
     * 
     * @param Request $request 
     * @Route("/create", name="editor_block_create") 
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
                return $this->redirect($this->generateUrl('editor_block_show', [
                    'id' => $block->getId()
                ]));
            } 
            return $this->render('@core-admin/block/editor/block-edit.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_block_list'));
        }
    }

    /**
     * Block Duplicate
     * 
     * @param int $id 
     * @Route("/duplicate/{id}", name="editor_block_duplicate") 
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
            return $this->redirect($this->generateUrl('editor_block_edit', [
                'id' => $newBlock->getId()
            ]));
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_block_list'));
        }        
    }

    /**
     * Block Edit
     * 
     * @param int $id
     * @param Request $request 
     * @Route("/update/{id}", name="editor_block_edit") 
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
                $options = $form->get('options')->getData();
                $options = $this->blockFactory->setOptionsData($options);
                $block->setOptions($options);
                $this->blockRepo->flush();
                /* dd($block->getOptions()); */
                $this->addFlash(
                    'info',
                    'Block updated'
                );
                return $this->redirect($this->generateUrl('editor_block_edit', [
                    'id' => $block->getId()
                ]));
            }
            /* dd($form->createView()); */
            return $this->render(
                '@core-admin/block/editor/block-edit.html.twig',
                [
                    'form' => $form->createView(),
                    'blockContainer' => $block
                ]
            ); 
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_block_list'));
        }
    }
   
    /**
     * Block Add media to block
     * 
     * @param int $blockId
     * @param string $type
     * @param int $mediaId
     * @Route("/add-media/{blockId}/{type}/{mediaId}",
     * name="editor_block_add_media",
     * defaults = {"type" = "image", "mediaId" = null}
     * )
     * @return Response
     * @return RedirectResponse
     */
    public function addMediaToBlock(int $blockId, string $type = 'image', int $mediaId = null): Response
    {
        try {
            $block = $this->blockVerificator($blockId);
            
            if ($mediaId) {
                $media = $this->mediaVerificator($mediaId);
                $block->setMedia($media);
                $this->blockRepo->flush();
                $this->addFlash(
                    'success',
                    'Media Added !'
                );
                return $this->redirect($this->generateUrl('editor_block_edit', [
                    'id' => $blockId
                ]));
            } 

            $medias = $this->fileRepo->findBy(['ext' => ['jpg', 'jpeg', 'png' ], 'private' => false]);
            return $this->render(
                '@core-admin/block/editor/block-media-editor.html.twig',
                [
                    'blockContainer' => $block,
                    'medias' => $medias
                ]
            );

        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
    }

    /**
     * Block Remove Media From Block
     * 
     * @param int $blockId
     * @Route("/remove-media/{blockId}",
     * name="editor_block_remove_media"
     * )
     * @return RedirectResponse
     */
    public function removeMediaFromBlock(int $blockId): RedirectResponse
    {
        try {
            $block = $this->blockVerificator($blockId);
            $block->setMedia(null);
            $this->blockRepo->flush();
            $this->addFlash(
                'success',
                'Media Added !'
            );
            return $this->redirect($this->generateUrl('editor_block_edit', [
                'id' => $blockId
            ]));

        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
        }
    }
    
    /**
     * Block Show
     * 
     * @param int $id
     * @Route("/show/{id}", name="editor_block_show") 
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
        
            return $this->render('@core-admin/block/editor/block-show.html.twig', [
                'blockContainer' => $blockContainer,
                'data' => $data
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_block_list'));
        }  
    }

    /**
     * Block Delete
     * 
     * @param int $id
     * @param Request $request 
     * @Route("/delete/{id}", name="editor_block_delete") 
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
        return $this->redirect($this->generateUrl('editor_block_list'));
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
            return $this->redirect($this->generateUrl('editor_block_list'));
        }
        return $block;
    }

    /**
     * Test if media exists and return it, or redirect to block list index with an error message
     * 
     * @param int $mediaId
     * @return File $media
     * @return RedirectResponse
     */
    public function mediaVerificator(int $mediaId)
    {
        $media = $this->fileRepo->find($mediaId);
        if (!$media) {
            $this->addFlash(
                'warning',
                'There is no Media  with id ' . $mediaId
            );
            return $this->redirect($this->generateUrl('editor_block_list'));
        }
        return $media;
    }
}
