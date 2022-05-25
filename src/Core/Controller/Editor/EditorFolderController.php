<?php

namespace App\Core\Controller\Editor;

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
 * Class EditorFolderController - To Handle Folder Management
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/folder")
 */
class EditorFolderController extends AbstractController
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
     * @Route("/", name="editor_folder_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $user = $this->getUser();
            $folders = $this->folderRepo->findFoldersByRoleAndFolder($user->getRoles());
            return $this->render('@core-admin/data/editor/folder-list.html.twig', [
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        } 
    }

    
    /**
     * Create Folder
     * 
     * @param int $folderId = null
     * @param Request $request
     * @Route("/create-folder/{folderId}", name="editor_folder_create",
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
                return $this->redirect($this->generateUrl('editor_folder_show', [
                    'id' => $parentFolder->getId()
                ]));
            }
            return $this->redirect($this->generateUrl('editor_folder_show', [
                'id' => $folder->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
    }

    /**
     * Edit a folder
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/{id}", name="editor_folder_edit")    
     * @return Response
     */
    public function editFolder(int $id, Request $request): Response
    {
        try {
            $folder = $this->folderVerificator($id);
            $form = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'edit',
                'mode' => 'edition'
            ]);
            $form->handleRequest($request);
            $folderNameForm = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'creation',
                'mode' => 'edit-name',
                'action' => $this->generateUrl('editor_folder_edit_name', [
                    'id' => $folder->getId()
                ]),
                'method' => 'POST',
            ]);
            if ($form->isSubmitted() && $form->isValid()) {
                $folder = $form->getData();
                $this->folderRepo->flush();
                $this->addFlash(
                    'info',
                    'Saved folder with new name : '.$folder->getName()
                );
                return $this->redirect($this->generateUrl('editor_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
            return $this->render('@core-admin/data/editor/folder-edit.html.twig', [
                'folder' => $folder,
                'form' => $form->createView(),
                'folderNameForm' =>$folderNameForm->createView()
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
    }

    /**
     * Edit a Folder Name
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/name/{id}", name="editor_folder_edit_name")    
     * @return RedirectResponse
     */
    public function editFolderName(int $id, Request $request): RedirectResponse
    {
        try {
            $folder = $this->folderVerificator($id);
            $folderNameForm = $this->createForm(FolderFormType::class, $folder, [
                'submitBtn' => 'creation',
                'mode' => 'edit-name',
                'action' => $this->generateUrl('editor_folder_edit_name', [
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
                return $this->redirect($this->generateUrl('editor_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_file_list'));
        }
    }

    /**
     * Edit a Folder Privacy
     * 
     * @param int $id
     * @param Request $request
     * @Route("/update/access/{id}/{accessValue}", name="editor_folder_access_edit",
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
                return $this->redirect($this->generateUrl('editor_folder_show', [
                    'id' => $folder->getId()
                ]));
            }
            if ($accessValue == 'public' && !$folder->getPrivate()) {
                $this->addFlash(
                    'warning',
                    'Folder Already Public'
                );
                return $this->redirect($this->generateUrl('editor_folder_show', [
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
            return $this->redirect($this->generateUrl('editor_folder_show', [
                'id' => $folder->getId()
            ]));
            
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
    }

    /**
     * Move a Folder to a folder
     * 
     * @param int $id
     * @param int $toFolderId
     * @Route("/move/{id}/to-folder/{toFolderId}", name="editor_move_folder")    
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
                    return $this->redirect($this->generateUrl('editor_folder_list'));
                }
                if ($folderToMove instanceof \Exception) {
                    $this->addFlash(
                        'danger',
                        $folderToMove->getMessage()
                    );
                    return $this->redirect($this->generateUrl('editor_folder_list'));
                }
            }
            $toFolder->add($folderToMove);
            $this->folderRepo->flush();
            return $this->redirect($this->generateUrl('editor_folder_show', [
                'id' => $toFolder->getId()
            ]));
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
    }
    /**
     * Show a folder
     * 
     * @param int $id
     * @Route("/show/{id}", name="editor_folder_show")   
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
                'action' => $this->generateUrl('editor_file_upload', ['folderId' => $folder->getId()]),
                'method' => 'POST',
            ]);
            return $this->render('@core-admin/data/editor/folder-show.html.twig', [
                'folder' => $folder,
                'uploadFileform' => $uploadFileform->createView(),
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
    }

    /**
     * Test if folder exist and return it or redirect to editor folder list with and error message
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
            return $this->redirect($this->generateUrl('editor_data_manager'));
        }
        return $folder;
    }

}
