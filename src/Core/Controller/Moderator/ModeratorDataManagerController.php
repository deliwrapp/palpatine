<?php

namespace App\Core\Controller\Moderator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\File;
use App\Core\Repository\FolderRepository;
use App\Core\Repository\FileRepository;
use App\Core\Form\FileUploadFormType;
use App\Core\Services\FileUploader;
use App\Core\Manager\FileManager;

/**
 * Class ModeratorDataManagerController - To Handle Data Management
 * @package App\Core\Controller\Moderator
 * @Route("/moderator/data")
 */
class ModeratorDataManagerController extends AbstractController
{
    /** @var FileUploader */
    private $fileUploader;

    /** @var FileManager $fileManager */
    private $fileManager;

    /** @var FolderRepository */
    private $folderRepo;

    /** @var FileRepository */
    private $fileRepo;

    public function __construct(
        FileUploader $fileUploader,
        FileManager $fileManager,
        FolderRepository $folderRepo,
        FileRepository $fileRepo
    )
    {
        $this->fileUploader = $fileUploader;
        $this->fileManager = $fileManager;
        $this->folderRepo = $folderRepo;
        $this->fileRepo = $fileRepo;
    }

    /**     
     * Moderator Data Manager Dashboard
     * 
     * @Route("/", name="moderator_data_manager") 
     * @return Response
     */
    public function index(): Response
    {
            $user = $this->getUser();
            $files = $this->fileRepo->findFilesByRoleAndFolder($user->getRoles());
            $folders = $this->folderRepo->findFoldersByRoleAndFolder($user->getRoles());
            $file = new File();
            $uploadFileform = $this->createForm(FileUploadFormType::class, $file, [
                'mode' => 'upload',
                'file_type' => 'default',
                'action' => $this->generateUrl('editor_file_upload'),
                'method' => 'POST',
            ]);
            return $this->render('@core-admin/data/moderator/data-manager.html.twig', [
                'uploadFileform' => $uploadFileform->createView(),
                'files' => $files,
                'folders' => $folders
            ]);
     
    }

}
