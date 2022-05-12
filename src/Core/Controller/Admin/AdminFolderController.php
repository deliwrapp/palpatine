<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\Folder;
use App\Core\Repository\FolderRepository;
use App\Core\Form\FolderFormType;
use App\Core\Services\FolderManager;

/**
 * Class AdminFolderController - To Handle Folder Management
 * @package App\Core\Controller\Admin
 * @Route("/admin/folder")
 */
class AdminFolderController extends AbstractController
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
     * Folders List Index
     * 
     * @Route("/", name="admin_folder_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $folders = $this->folderRepo->findAll();
            return $this->render('@core-admin/data/folder-list.html.twig', [
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('AdminDashboard'));
        } 
    }

    /**
     * Edit a folder
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}", name="admin_folder_edit")    
     * @return Response
     */
    public function editFolder(int $id, Request $request): Response
    {
        try {
            
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_folder_list'));
        }
    }

    /**
     * Show a folder
     * 
     * @param int $id
     * @Route("/show/{id}", name="admin_folder_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $folder = $this->folderVerificator($id);
            return $this->render('@core-admin/data/folder-show.html.twig', [
                'folder' => $folder
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_folder_list'));
        }
    }

    /**
     * Delete a folder (database reference and original folder) and all the contents (folder and files)
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="admin_folder_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-folder', $submittedToken)) {
                $folder = $this->folderVerificator($id);
                $folder = $this->folderManager->remove($folder);
                if (!$folder) {
                    $this->addFlash(
                        'warning',
                        'Error during deletion'
                    );
                } elseif ($folder instanceof \Exception) {
                    $this->addFlash(
                        'danger',
                        'Error during deletion : ' . $folder->getMessage()
                    );
                }  else {
                    $this->addFlash(
                        'success',
                        'The Folder and all the contents have been deleted with have been deleted '
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
        return $this->redirect($this->generateUrl('admin_folder_list'));
    }

    /**
     * Test if folder exist and return it or redirect to admin folder list with and error message
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
            return $this->redirect($this->generateUrl('admin_folder_list'));
        }
        return $folder;
    }

}
