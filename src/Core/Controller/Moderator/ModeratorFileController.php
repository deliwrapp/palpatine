<?php

namespace App\Core\Controller\Moderator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;
use App\Core\Manager\FileManager;

/**
 * Class ModeratorFileController - To Handle File Upload and File Management
 * @package App\Core\Controller\Moderator
 * @IsGranted("ROLE_MODERATOR",statusCode=401, message="No access! Get out!")
 * @Route("/moderator/file")
 */
class ModeratorFileController extends AbstractController
{

    /** @var FileManager $fileManager*/
    private $fileManager;

    /** @var FileRepository $fileRepo */
    private $fileRepo;

    public function __construct(
        FileManager $fileManager,
        FileRepository $fileRepo
    )
    {
        $this->fileManager = $fileManager;
        $this->fileRepo = $fileRepo;
    }

    /**      
     * Moderator Files List Index
     * 
     * @Route("/", name="moderator_file_list")  
     * @return Response
     */
    public function index(): Response
    {
        try {
            $user = $this->getUser();
            $files = $this->fileRepo->findFilesByRoleAndFolder($user->getRoles());
            return $this->render('@core-admin/data/moderator/file-list.html.twig', [
                'files' => $files
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
     * Show a file
     * 
     * @param int $id
     * @Route("/show/{id}", name="moderator_file_show")   
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $file = $this->fileVerificator($id);
            return $this->render('@core-admin/data/moderator/file-show.html.twig', [
                'file' => $file
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
     * Test if file exist and return it or redirect to moderator file list with and error message
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
            return $this->redirect($this->generateUrl('moderator_data_manager'));
        }
        return $file;
    }

}
