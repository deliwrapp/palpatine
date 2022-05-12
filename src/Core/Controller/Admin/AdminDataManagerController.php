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

/**
 * Class AdminDataManagerController - To Handle Data Management
 * @package App\Core\Controller\Admin
 * @Route("/admin/data")
 */
class AdminFileController extends AbstractController
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

}
