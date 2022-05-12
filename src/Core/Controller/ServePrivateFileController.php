<?php

namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;

/**
 * Class ServePrivateFileController - To Handle File Serve and File Dowland
 * @package App\Core\Controller
 * @Route("/data")
 */
class ServePrivateFileController extends AbstractController
{

    /** @var FileRepository */
    private $fileRepo;

    public function __construct(
        FileRepository $fileRepo
    )
    {
        $this->fileRepo = $fileRepo;
    }

    /**
     * Returns a file for download.
     *
     * @param string $filename
     * @Route("/downland-private-file/{filename}", name="download_private_file", methods="GET")
     * @return BinaryFileResponse
     */
    public function privateFileDownload(string $filename): BinaryFileResponse
    {
        try {
            $file = $this->fileRepo->findOneBy(['originalFilename' => $filename]);
            if (!$file) {
                return $this->errorHandler(404, 'There is no file  with filename ' . $filename);
            }
            $name = $file->getName().'.'.$file->getExt();
            return $this->fileDownload($file->getFilePath(), $name);

        }  catch (\Exception $e) {
            return $this->errorHandler(503, $e->getMessage());
        }
    }

    /**
     * Returns a file for display.
     *
     * @param string $filename
     * @Route("/serve-private-file/{filename}", name="serve_private_file", methods="GET")
     * @return BinaryFileResponse
     */
    public function privateFileServe(string $filename): BinaryFileResponse
    {
        try {
            $file = $this->fileRepo->findOneBy(['originalFilename' => $filename]);
            if (!$file) {
                return $this->errorHandler(404, 'There is no file  with filename ' . $filename);
            }
            return $this->fileServe($file->getFilePath());

        }  catch (\Exception $e) {
            return $this->errorHandler(503, $e->getMessage());
        }
    }

    /**
     * Returns a private file for download.
     *
     * @param string $path
     * @param string $name
     * @return BinaryFileResponse
     */
    private function fileDownload(string $path, string $name): BinaryFileResponse
    {
        $absolutePath = $this->getParameter('kernel.project_dir') . '/' . $path;
        $response = new BinaryFileResponse($absolutePath);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $name
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    /**
     * Returns a private file for display.
     *
     * @param string $path
     * @return BinaryFileResponse
     */
    private function fileServe(string $path): BinaryFileResponse
    {
        $absolutePath = $this->getParameter('kernel.project_dir') . '/' . $path;
        return new BinaryFileResponse($absolutePath);
    }

    /**
     * Returns a error response.
     *
     * @param int $code
     * @param string $message
     * @throws HttpException
     */
    private function errorHandler(int $code, string $message): BinaryFileResponse
    {
        throw new HttpException($code, $message);
    }
}