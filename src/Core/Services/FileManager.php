<?php

// src/Core/Services/FileManager.php
namespace App\Core\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Core\Entity\File;
use App\Core\Repository\FileRepository;
 
class FileManager
{
    
    /** @var FileRepository */
    private $fileRepo;

    /** @var SluggerInterface */
    private $slugger;

    /** @var string */
    private $projectDirectory;

    /** @var string */
    private $publicDirectory;

    /** @var string */
    private $absolutePublicPath;

    /** @var string */
    private $privateDirectory;

    /** @var string */
    private $absolutePrivatePath;

    /** @var string */
    private $publicUploadPath;

    /** @var string */
    private $privateUploadPath;

    /** @var ParameterBagInterface */
    private $params;
 
    public function __construct(
        FileRepository $fileRepo,
        SluggerInterface $slugger,
        ParameterBagInterface $params
    )
    {
        $this->fileRepo =  $fileRepo;
        $this->slugger = $slugger;
        $this->projectDirectory = $params->get('kernel.project_dir');
        $this->publicDirectory = $params->get('public_directory');
        $this->privateDirectory = $params->get('private_directory');
        $this->absolutePublicPath = $params->get('absolute_public_directory');
        $this->absolutePrivatePath = $params->get('absolute_private_directory');
        $this->publicUploadPath = $params->get('public_uploads_directory');
        $this->privateUploadPath = $params->get('private_uploads_directory');
    }
 
    /**
     * Create File
     *
     * @param string $name
     * @return string
     */
    public function create(string $name = null)
    {
        try {
            
        } catch (FileException $e) {
            return $e;
        }
        return $name;
    }

    /**
     * Rename File
     *
     * @param File $file
     * @param string $name
     * @return File $file
     * @return IOExceptionInterface $e
     */
    public function rename(File $file, string $name)
    {
        try {
            $filesystem = new Filesystem();
            $absoluteFilePath = $this->projectDirectory.'/'. $file->getFilePath();
            if ($filesystem->exists($absoluteFilePath)) {
                $newSafeFilename = $this->slugger->slug($name).'-'.uniqid();
                $file->setName($name);
                $file->setOriginalFilename($newSafeFilename);
                if ($file->getPrivate()) {
                    $file->setPath($this->privateDirectory.'/'.$this->privateUploadPath);
                } else {
                    $file->setPath($this->publicUploadPath);
                }
                $newAbsoluteFilePath = $this->projectDirectory.'/'. $file->getFilePath();
                $filesystem->rename($absoluteFilePath, $newAbsoluteFilePath);
                $this->fileRepo->flush();
                return $file;
            } else {
                return false;
            }  
        } catch (IOExceptionInterface $e) {
            return $e;
        }
        return $name;
    }
    
    /**
     * Remove File
     *
     * @param File $file
     * @return bool
     * @return IOExceptionInterface $e
     */
    public function remove(File $file)
    {
        try {
            $filesystem = new Filesystem();
            if ($file->getPrivate()) {
                $absolutePath = $this->projectDirectory.'/'. $file->getFilePath();
            } else {
                $absolutePath = $this->projectDirectory.'/'.$this->publicDirectory.'/'. $file->getFilePath();
            }
            if ($filesystem->exists($absolutePath)) {
                $filesystem->remove($absolutePath);
                $this->fileRepo->remove($file);
                return true;
            } else {
                return false;
            } 
        } catch (IOExceptionInterface $e) {
            return $e;
        }
    }

    /**
     * Switch between private and public folder
     *
     * @param File $file
     * @param bool $private
     * @return bool
     * @return \Exception $e
     */
    public function switchPrivateToPublic(File $file, $private = false)
    {
        try {
            $file->setPrivate($private);
            $this->fileRepo->add($file, false);
            return $this->rename($file, $file->getName());
        } catch (\Exception $e) {
            return $e;
        }
        return $name;
    }

    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }
    public function getPrivateDirectory()
    {
        return $this->privateDirectory;
    }
    public function getPublicDirectory()
    {
        return $this->publicDirectory;
    }

    public function getPrivateUploadPath()
    {
        return $this->privateUploadPath;
    }
    public function getPublicUploadPath()
    {
        return $this->publicUploadPath;
    }

    public function getFullPrivateUploadPath()
    {
        return $this->absolutePrivatePath.'/'.$this->privateUploadPath;
    }
    public function getFullPublicUploadPath()
    {
        return $this->absolutePublicPath.'/'.$this->publicUploadPath;
    }

}
