<?php

namespace App\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Folder;
use App\Core\Entity\File;
use App\Core\Repository\FolderRepository;
use App\Core\Form\FolderFormType;
use App\Core\Form\FileUploadFormType;
use App\Core\Manager\FolderManager;

/**
 * Class AdminFolderController - To Handle Folder Management
 * @package App\Core\Controller\Admin
 * @IsGranted("ROLE_ADMIN",statusCode=401, message="No access! Get out!")
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
            return $this->render('@core-admin/data/admin/folder-list.html.twig', [
                'folders' => $folders
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
     * Create Folder
     * 
     * @param int $folderId = null
     * @param Request $request
     * @Route("/create-folder/{folderId}", name="admin_folder_create",
     * defaults={"folderId": null}
     * )
     * @return Response
     * @return RedirectResponse
    */
    public function create(Request $request, int $folderId = null): Response
    {
        try {
            $parentFolder = false;
            $folder = new Folder();
            if ($folderId) {
                $parentFolder = $this->folderVerificator($folderId);
                $parentFolder->addSubFolder($folder);
                $folder->setPrivate($parentFolder->getPrivate());
            }
            $this->folderRepo->add($folder);
            $this->addFlash(
                'info',
                'Saved new Folder with id '.$folder->getId()
            );
            if ($parentFolder) {
                return $this->redirect($this->generateUrl('admin_folder_show', [
                    'id' => $parentFolder->getId()
                ]));
            }
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
            $folder = $this->folderVerificator($id);
            $form = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'creation',
                'mode' => 'edition'
            ]);
            $form->handleRequest($request);
            $folderNameForm = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'creation',
                'mode' => 'edit-name',
                'action' => $this->generateUrl('admin_folder_edit_name', [
                    'id' => $folder->getId()
                ]),
                'method' => 'POST',
            ]);
            if ($form->isSubmitted() && $form->isValid()) {
                $folder = $form->getData();
                $this->folderRepo->flush();
                $this->addFlash(
                    'info',
                    'Saved new Folder with id '.$folder->getId()
                );
                return $this->redirect($this->generateUrl('admin_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
            return $this->render('@core-admin/data/admin/folder-edit.html.twig', [
                'folder' => $folder,
                'form' => $form->createView(),
                'folderNameForm' =>$folderNameForm->createView()
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
     * Edit a Folder Name
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/name/{id}", name="admin_folder_edit_name")    
     * @return RedirectResponse
     */
    public function editFolderName(int $id, Request $request): RedirectResponse
    {
        try {
            $folder = $this->folderVerificator($id);
            $folderNameForm = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'creation',
                'mode' => 'edit-name',
                'action' => $this->generateUrl('admin_folder_edit_name', [
                    'id' => $folder->getId()
                ]),
                'method' => 'POST',
            ]);
            $folderNameForm->handleRequest($request);

            if ($folderNameForm->isSubmitted() && $folderNameForm->isValid()) {
                $name = $folderNameForm->get('name')->getData();
                $folder = $this->folderManager->rename($folder, $name);
                if ($folder instanceof \Exception) {
                    $this->addFlash(
                        'info',
                        'Error : '.$folder->getMessage()
                    );
                }
                $this->addFlash(
                    'info',
                    'Saved Folder name updated '
                );
                return $this->redirect($this->generateUrl('admin_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_file_list'));
        }
    }

    /**
     * Edit a Folder Privacy
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/access/{id}/{accessValue}", name="admin_folder_access_edit",
     * defaults={"accessValue": "private"}
     *)    
     * @return RedirectResponse
     */
    public function editFolderPrivate(int $id, string $accessValue, Request $request): RedirectResponse
    {
        try {
            $folder = $this->folderVerificator($id);
            if ($accessValue == 'private' && $folder->getPrivate()) {
                $this->addFlash(
                    'warning',
                    'Folder Already Private'
                );
                return $this->redirect($this->generateUrl('admin_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
            if ($accessValue == 'public' && !$folder->getPrivate()) {
                $this->addFlash(
                    'warning',
                    'Folder Already Public'
                );
                return $this->redirect($this->generateUrl('admin_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
            if ($accessValue == 'private') {
                $this->folderManager->switchPrivateToPublic($folder, true);
            }
            if ($accessValue == 'public') {
                $this->folderManager->switchPrivateToPublic($folder, false);
            }
            $this->addFlash(
                'success',
                'Folder Access updated with value '.$accessValue
            );
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
     * Move a Folder to a folder
     * 
     * @param int $id
     * @param int $toFolderId
     * @Route("/move/{id}/to-folder/{toFolderId}", name="admin_move_folder")    
     * @return Response
     */
    public function moveFolderTo(int $id, int $toFolderId): Response
    {
        try {
            $folderToMove = $this->folderVerificator($id);
            $toFolder = $this->folderVerificator($toFolderId);
            if ($toFolder->getPrivate() != $folderToMove->getPrivate()) {
                $folderToMove = $this->fileManager->switchPrivateToPublic($folderToMove, $toFolder->getPrivate() );
                if (!$folderToMove) {
                    $this->addFlash(
                        'danger',
                        'Error during process'
                    );
                    return $this->redirect($this->generateUrl('admin_folder_list'));
                }
                if ($folderToMove instanceof \Exception) {
                    $this->addFlash(
                        'danger',
                        $folderToMove->getMessage()
                    );
                    return $this->redirect($this->generateUrl('admin_folder_list'));
                }
            }
            $folderToMove->setRoleAccess($toFolder->getRoleAccess());
            $toFolder->addSubFolder($folderToMove);
            $this->folderRepo->flush();
            return $this->redirect($this->generateUrl('admin_folder_show', [
                'id' => $toFolder->getId()
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
            $file = new File();
            $file->setPrivate($folder->getPrivate());
            $uploadFileform = $this->createForm(FileUploadFormType::class, $file, [
                'mode' => 'upload',
                'file_type' => 'default',
                'action' => $this->generateUrl('admin_file_upload', ['folderId' => $folder->getId()]),
                'method' => 'POST',
            ]);
            return $this->render('@core-admin/data/admin/folder-show.html.twig', [
                'folder' => $folder,
                'uploadFileform' => $uploadFileform->createView(),
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
        return $this->redirect($this->generateUrl('admin_data_manager'));
    }

    /**
     * Test if folder exist and return it or redirect to admin folder list with and error message
     * 
     * @param int $folderId   
     * @return Folder $folder    
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
            return $this->redirect($this->generateUrl('admin_data_manager'));
        }
        return $folder;
    }

}
