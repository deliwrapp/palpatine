<?php

namespace App\Core\Controller\Editor;

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
 * Class EditorDataManagerController - To Handle Data Management
 * @package App\Core\Controller\Editor
 * @IsGranted("ROLE_EDITOR",statusCode=401, message="No access! Get out!")
 * @Route("/editor/data")
 */
class EditorDataManagerController extends AbstractController
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
     * Editor Data Manager Dashboard
     * 
     * @Route("/", name="editor_data_manager") 
     * @return Response
     */
    public function index(): Response
    {
        try {
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
            return $this->render('@core-admin/data/editor/data-manager.html.twig', [
                'uploadFileform' => $uploadFileform->createView(),
                'files' => $files,
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('editor_dashboard'));
        } 
    }

}
