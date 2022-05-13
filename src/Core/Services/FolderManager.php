<?php

// src/Core/Services/FolderUploader.php
namespace App\Core\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Core\Repository\FolderRepository;
use App\Core\Entity\Folder;
use App\Core\Entity\File;
use App\Core\Services\FileManager;
 
class FolderManager
{
    
    /** @var FileManager */
    private $fileManager;
    
    /** @var FolderRepository */
    private $folderRepo;

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
        FileManager $fileManager,
        FolderRepository $folderRepo,
        SluggerInterface $slugger,
        ParameterBagInterface $params
    )
    {
        $this->fileManager =  $fileManager;
        $this->folderRepo =  $folderRepo;
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
     * Create Folder
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
     * Rename Folder
     *
     * @param Folder $folder
     * @param string $name
     * @return Folder $folder
     * @return Exception $e
     */
    public function rename(Folder $folder, string $name)
    {
        try {
            $newSafeFilename = $this->slugger->slug($name).'-'.uniqid();
            $folder->setName($name);
            $folder->setOriginalFilename($newSafeFilename);
            $this->folderRepo->flush();
            return $folder; 
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    /**
     * Remove Folder and all the content
     *
     * @param Folder $folder
     * @return bool
     * @return Exception $e
     */
    public function remove(Folder $folder)
    {
        try {
            $this->removeFolderContent($folder);
            $this->folderRepo->remove($folder);
            $this->folderRepo->flush();
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Remove Folder Files Content
     *
     * @param File $folder
     * @return bool
     * @return Exception $e
     */
    public function removeFolderContent(Folder $folder)
    {
        try {
            if ($folder->getFiles()) {
                foreach ($folder->getFiles() as $file) {
                    $fileTest = $this->fileManager->remove($file);
                    if (!$fileTest) {
                        $file->setFolder(null);
                    }
                }
            }
            if ($folder->getSubFolders()) {
                foreach ($folder->getSubFolders() as $subFolder) {
                    $this->removeFolderContent($subFolder);
                }
            }
            $this->folderRepo->remove($folder, false);
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Switch between private and public folder
     *
     * @param Folder $folder
     * @param bool $private
     * @return bool
     * @return \Exception $e
     */
    public function switchPrivateToPublic(Folder $folder, $private = false)
    {
        try {
            $this->switchPrivateToPublicFolderContent($folder, $private);
            if ($folder->getFolder()) {
                $folder->getFolder()->removeSubFolder($folder);
                $folder->setFolder(null);
            }
            $this->folderRepo->add($folder);
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Switch Private To Public / Public To Private - Folder Files Content
     *
     * @param Folder $folder
     * @param bool $private
     * @return bool
     * @return Exception $e
     */
    public function switchPrivateToPublicFolderContent(Folder $folder, ?bool $private)
    {
        try {
            if ($folder->getFiles()) {
                foreach ($folder->getFiles() as $file) {
                    $this->fileManager->switchPrivateToPublic($file, $private);
                }
            }
            if ($folder->getSubFolders()) {
                foreach ($folder->getSubFolders() as $subFolder) {
                    $this->switchPrivateToPublicFolderContent($subFolder, $private);
                }
            }
            $folder->setPrivate($private);
            return true;
        } catch (\Exception $e) {
            return $e;
        }
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
