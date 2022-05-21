<?php

namespace App\Core\Controller\PublicAccess;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\FolderRepository;
use App\Core\Repository\FileRepository;
use App\Core\Services\FileUploader;
use App\Core\Manager\FileManager;

/**
 * Class PublicDataManagerController - To Handle Data Management
 * @package App\Core\Controller\PublicAccess
 * @Route("/public/data")
 */
class PublicDataManagerController extends AbstractController
{
    /** @var FileUploader */
    private $fileUploader;

    /** @var FileManager */
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
     * Data Manager Dashboard
     * 
     * @Route("/", name="public_data_manager") 
     * @return Response
     */
    public function index(): Response
    {
        try {
            $files = $this->fileRepo->findBy(['private' => false, 'folder' => null]);
            $folders = $this->folderRepo->findBy(['private' => false, 'folder' => null]);
            
            return $this->render('@base-theme/data/data-manager.html.twig', [
                'files' => $files,
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('admin_dashboard_redirect'));
        } 
    }

}
