<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;
use App\Core\Repository\FolderRepository;
use App\Core\Form\FileUploadFormType;
use App\Core\Services\FileUploader;
use App\Core\Services\FileManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class AdminFileController - To Handle File Upload and File Management
 * @package App\Core\Controller\Admin
 * @Route("/admin/file")
 */
class AdminFileController extends AbstractController
{
    /** @var FileUploader */
    private $fileUploader;

    /** @var FileManager */
    private $fileManager;

    /** @var FileRepository */
    private $fileRepo;

    /** @var FolderRepository */
    private $folderRepo;

    public function __construct(
        FileUploader $fileUploader,
        FileManager $fileManager,
        FileRepository $fileRepo,
        FolderRepository $folderRepo
    )
    {
        $this->fileUploader = $fileUploader;
        $this->fileManager = $fileManager;
        $this->fileRepo = $fileRepo;
        $this->folderRepo = $folderRepo;
    }

    /**      
     * Files List Index
     * 
     * @Route("/", name="admin_file_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $files = $this->fileRepo->findAll();
            return $this->render('@core-admin/data/file-list.html.twig', [
                'files' => $files
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        } 
    }

    /**     
     * Upload File Handler
     * 
     * @param int|null $folderId
     * @param Request $request
     * @Route("/upload-file/{folderId}", name="admin_file_upload",
     * defaults={"folderId": null}
     * )   
     * @return RedirectResponse
    */
    public function uploadFile(Request $request, int $folderId = null): RedirectResponse
    {
        try {
            if ($folderId) {                
                $folder = $this->folderVerificator($folderId);
            }
            $file = new File();
            $uploadFileform = $this->createForm(FileUploadFormType::class, $file, [
                'mode' => 'upload',
                'file_type' => 'default',
                'action' => $this->generateUrl('admin_file_upload', ['folderId' => $folderId]),
                'method' => 'POST',
            ]);
            $uploadFileform->handleRequest($request);
            
            if ($uploadFileform->isSubmitted()) 
            {
                $fileToUpload = $uploadFileform->get('upload_file')->getData();
                if ($fileToUpload) 
                {
                    $fileName = $uploadFileform['name']->getData();
                    if ($folderId) {
                        $private = $folder->getPrivate();
                    } else {
                        $private = $uploadFileform['private']->getData();
                    }
                    // upload the file and save it
                    $file = $this->fileUploader->upload($fileToUpload, $file, $private, $fileName);
                    if ($file instanceof File)
                    {
                        // set its values to the file entity 
                        $file->setRoleAccess($uploadFileform['roleAccess']->getData());
                        $file->setDescription($uploadFileform['description']->getData());
                        $file->setIsPublished($uploadFileform['isPublished']->getData());
                        if ($folderId) {
                            $folder->addFile($file);
                            $this->folderRepo->add($folder);
                        }
                        $this->fileRepo->add($file);
                        $this->addFlash(
                            'info',
                            'Add new File with name '.$file->getName()
                        );
                    }
                    else
                    {
                        if ($file instanceof FileException || $file instanceof \Exception) {
                            $this->addFlash(
                                'danger',
                                $file->getMessage()
                            );
                        } else {
                            $this->addFlash(
                                'danger',
                                'Oups, an error occured during the upload!!!'
                            );
                        }
                    }
                } else {
                    $this->addFlash(
                        'danger',
                        'Oups, There is no file to upload!!!'
                    );
                }
            }
            else {
                $this->addFlash(
                    'danger',
                    'Oups, There is no file to upload!!!'
                );
            }
            if ($folderId) {                
                return $this->redirect($this->generateUrl('admin_folder_show', ['id' => $folderId]));
            }
            return $this->redirect($this->generateUrl('admin_data_manager'));
    
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Edit a file
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}", name="admin_file_edit")    
     * @return Response
     */
    public function editFile(int $id, Request $request): Response
    {
        try {
            
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Edit a file
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/name/{id}", name="admin_file_name_edit")    
     * @return RedirectResponse
     */
    public function editFileName(int $id, Request $request): RedirectResponse
    {
        try {
            
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Edit a file
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/private/{id}", name="admin_file_private_edit")    
     * @return RedirectResponse
     */
    public function editFilePrivate(int $id, Request $request): RedirectResponse
    {
        try {
            
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Move a file to a folder
     * 
     * @param int $id
     * @param int $folderId
     * @Route("/move/{id}/to-folder/{folderId}", name="admin_move_file")    
     * @return Response
     */
    public function moveFileTo(int $id, int $folderId): Response
    {
        try {
            $file = $this->fileVerificator($id);
            $folder = $this->folderVerificator($folderId);
            if ($folder->getPrivate() != $file->getPrivate()) {
                $file = $this->fileManager->switchPrivateToPublic($file, $folder->getPrivate());
                if (!$file) {
                    $this->addFlash(
                        'danger',
                        'Error during process'
                    );
                    return $this->redirect($this->generateUrl('admin_file_list'));
                }
                if ($file instanceof \Exception) {
                    $this->addFlash(
                        'danger',
                        $file->getMessage()
                    );
                    return $this->redirect($this->generateUrl('admin_file_list'));
                }
            }
            $folder->add($file);
            $this->folderRepo->flush();
            return $this->redirect($this->generateUrl('admin_folder_show', [
                'id' => $folder->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Show a file
     * 
     * @param int $id
     * @Route("/show/{id}", name="admin_file_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $file = $this->fileVerificator($id);
            return $this->render('@core-admin/data/file-show.html.twig', [
                'file' => $file
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
    }

    /**
     * Delete a file (database reference and original file)
     * 
     * @param int $id
     * @param Request $request
     * @Route("/delete/{id}", name="admin_file_delete")
     * @return RedirectResponse
     */
    public function delete(int $id, Request $request): RedirectResponse
    {
        try {
            $submittedToken = $request->request->get('token'); 
            if ($this->isCsrfTokenValid('delete-file', $submittedToken)) {
                $file = $this->fileVerificator($id);
                $file = $this->fileManager->remove($file);
                if (!$file) {
                    $this->addFlash(
                        'warning',
                        'Can not delete file with this id : ' . $id
                    );
                } else {
                    $this->addFlash(
                        'success',
                        'The File with have been deleted '
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
        return $this->redirect($this->generateUrl('admin_data_manager'));
    }

    /**
     * Test if file exist and return it or redirect to admin file list with and error message
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
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
        return $file;
    }

    /**
     * Test if folder exist and return it or redirect to admin file list with and error message
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
                'There is no Folder with id ' . $folderId
            );
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
        return $folder;
    }

}
