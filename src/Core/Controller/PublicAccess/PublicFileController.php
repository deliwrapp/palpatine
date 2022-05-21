<?php

namespace App\Core\Controller\PublicAccess;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;
use App\Core\Manager\FileManager;

/**
 * Class PublicFileController - To Handle File Upload and File Management
 * @package App\Core\Controller\Public
 * @Route("/public/file")
 */
class PublicFileController extends AbstractController
{

    /** @var FileManager $fileManager*/
    private $fileManager;

    /** @var FileRepository $fileRepo */
    private $fileRepo;

    public function __construct(
        FileManager $fileManager,
        FileRepository $fileRepo
    )
    {
        $this->fileManager = $fileManager;
        $this->fileRepo = $fileRepo;
    }

    /**      
     * Public Files List Index
     * 
     * @Route("/", name="public_file_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $files = $this->fileRepo->findBy(['private' => false]);
            return $this->render('@base-theme/data/file-list.html.twig', [
                'files' => $files
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        } 
    }

    /**
     * public Show file
     * 
     * @param int $id
     * @Route("/show/{id}", name="public_file_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $file = $this->fileVerificator($id);
            return $this->render('@base-theme/data/file-show.html.twig', [
                'file' => $file
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        }
    }

    /**
     * Test if file exist and is not private and return it or redirect to public file list with and error message
     * 
     * @param int $fileId   
     * @return File     
     * @return RedirectResponse
     */
    public function fileVerificator(int $fileId)
    {
        $file = $this->fileRepo->find($fileId);
        if (!$file) {
            $this->addFlash(
                'warning',
                'There is no File  with id ' . $fileId
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        }
        if ($file->getPrivate()) {
            $this->addFlash(
                'warning',
                'You can access to this file'
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        }
        return $file;
    }

}
