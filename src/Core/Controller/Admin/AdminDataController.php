<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;
use App\Core\Form\FileUploadFormType;
use App\Core\Services\FileUploader;
use App\Core\Services\FileManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class AdminDataController - To Handle File Upload and File Management
 * @package App\Core\Controller\Admin
 * @Route("/admin/data")
 */
class AdminDataController extends AbstractController
{
    /** @var FileUploader */
    private $fileUploader;

    /** @var FileManager */
    private $fileManager;

    /** @var FileRepository */
    private $fileRepo;

    public function __construct(
        FileUploader $fileUploader,
        FileManager $fileManager,
        FileRepository $fileRepo
    )
    {
        $this->fileUploader = $fileUploader;
        $this->fileManager = $fileManager;
        $this->fileRepo = $fileRepo;
    }

    /**     
     * Data Manager Dashboard
     * 
     * @Route("/", name="admin_data_manager") 
     * @return Response
     */
    public function index(): Response
    {
        try {
            $file = new File();
            $uploadFileform = $this->createForm(FileUploadFormType::class, $file, [
                'mode' => 'upload',
                'file_type' => 'default',
                'action' => $this->generateUrl('admin_file_upload'),
                'method' => 'POST',
            ]);
            return $this->render('@core-admin/data/data-manager.html.twig', [
                'uploadFileform' => $uploadFileform->createView(),
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
     * Files List Index
     * 
     * @Route("/files", name="admin_file_list")  
     * @return Response
     */
    public function filesList(): Response
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
            return $this->redirect($this->generateUrl('AdminDashboard'));
        } 
    }

    /**     
     * Upload File Handler
     * 
     * @param Request $request
     * @Route("/upload-file", name="admin_file_upload")   
     * @return RedirectResponse
    */
    public function uploadFile(Request $request): RedirectResponse
    {
        try {
            $file = new File();
            $uploadFileform = $this->createForm(FileUploadFormType::class, $file, [
                'mode' => 'upload',
                'file_type' => 'default',
                'action' => $this->generateUrl('admin_file_upload'),
                'method' => 'POST',
            ]);
            $uploadFileform->handleRequest($request);
            
            /* dd($uploadFileform); */
            if ($uploadFileform->isSubmitted()) 
            {
                $fileToUpload = $uploadFileform->get('upload_file')->getData();
                if ($fileToUpload) 
                {
                    $fileName = $uploadFileform['name']->getData();
                    $private = $uploadFileform['private']->getData();
                    // upload the file and save it
                    $file = $this->fileUploader->upload($fileToUpload, $file, $private, $fileName);
                    if ($file instanceof File)
                    {
                        // set its values to the file entity 
                        $file->setRoleAccess($uploadFileform['roleAccess']->getData());
                        $file->setDescription($uploadFileform['description']->getData());
                        $file->setIsPublished($uploadFileform['isPublished']->getData());
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
            return $this->redirect($this->generateUrl('admin_file_list'));
    
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_file_list'));
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
            return $this->redirect($this->generateUrl('admin_file_list'));
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
            return $this->redirect($this->generateUrl('admin_file_list'));
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
        return $this->redirect($this->generateUrl('admin_file_list'));
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
            return $this->redirect($this->generateUrl('admin_file_list'));
        }
        return $file;
    }

}
