<?php

namespace App\Core\Controller\PublicAccess;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Folder;
use App\Core\Repository\FolderRepository;
use App\Core\Manager\FolderManager;

/**
 * Class PublicFolderController - To Handle Folder Management
 * @package App\Core\Controller\Public
 * @Route("/public/folder")
 */
class PublicFolderController extends AbstractController
{
    /** @var FolderManager */
    private $folderManager;

    /** @var FolderRepository */
    private $folderRepo;

    public function __construct(
        FolderManager $folderManager,
        FolderRepository $folderRepo
    )
    {
        $this->folderManager = $folderManager;
        $this->folderRepo = $folderRepo;
    }

    /**      
     * Folders Public List Index
     * 
     * @Route("/", name="public_folder_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $folders = $this->folderRepo->findBy(['private' => false]);
            return $this->render('@base-theme/data/folder-list.html.twig', [
                'folders' => $folders
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
     * Public Show Folder
     * 
     * @param int $id
     * @Route("/show/{id}", name="public_folder_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $folder = $this->folderVerificator($id);
            return $this->render('@base-theme/data/folder-show.html.twig', [
                'folder' => $folder
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
     * Test if folder exist and is not private and return it or redirect to public folder list with and error message
     * 
     * @param int $folderId   
     * @return Folder     
     * @return RedirectResponse
     */
    public function folderVerificator(int $folderId)
    {
        $folder = $this->folderRepo->find($folderId);
        if (!$folder) {
            $this->addFlash(
                'warning',
                'There is no Folder  with id ' . $folderId
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        }
        if ($folder->getPrivate()) {
            $this->addFlash(
                'warning',
                'You can access to this folder'
            );
            return $this->redirect($this->generateUrl('public_data_manager'));
        }
        return $folder;
    }

}
