<?php

namespace App\Core\Controller\Moderator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\Folder;
use App\Core\Repository\FolderRepository;
use App\Core\Manager\FolderManager;

/**
 * Class ModeratorFolderController - To Handle Folder Management
 * @package App\Core\Controller\Moderator
 * @IsGranted("ROLE_MODERATOR",statusCode=401, message="No access! Get out!")
 * @Route("/moderator/folder")
 */
class ModeratorFolderController extends AbstractController
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
     * @Route("/", name="moderator_folder_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $user = $this->getUser();
            $folders = $this->folderRepo->findFoldersByRoleAndFolder($user->getRoles());
            return $this->render('@core-admin/data/moderator/folder-list.html.twig', [
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('moderator_data_manager'));
        } 
    }

    /**
     * Show a folder
     * 
     * @param int $id
     * @Route("/show/{id}", name="moderator_folder_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $folder = $this->folderVerificator($id);
            return $this->render('@core-admin/data/moderator/folder-show.html.twig', [
                'folder' => $folder
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('moderator_data_manager'));
        }
    }

    /**
     * Test if folder exist and return it or redirect to moderator folder list with and error message
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
            return $this->redirect($this->generateUrl('moderator_data_manager'));
        }
        return $folder;
    }

}
