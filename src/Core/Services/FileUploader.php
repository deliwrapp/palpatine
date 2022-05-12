<?php

// src/Core/Services/FileUploader.php
namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Core\Entity\File;
 
class FileUploader
{
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

    /** @var SluggerInterface */
    private $slugger;

    /** @var UrlHelper */
    private $urlHelper;

    /** @var ParameterBagInterface */
    private $params;
 
    public function __construct(
        ParameterBagInterface $params,
        SluggerInterface $slugger,
        UrlHelper $urlHelper
    )
    {
        $this->privateDirectory = $params->get('private_directory');
        $this->absolutePublicPath = $params->get('absolute_public_directory');
        $this->absolutePrivatePath = $params->get('absolute_private_directory');
        $this->publicUploadPath = $params->get('public_uploads_directory');
        $this->privateUploadPath = $params->get('private_uploads_directory');
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;
    }
 
    /**
     * Upload a new File
     *
     * @param UploadedFile $uploadedFile
     * @param File $file
     * @param bool $private
     * @param string $name
     * @return File $file
     * @return FileException $e
     */
    public function upload(UploadedFile $uploadedFile, File $file, bool $private = false, string $name = null)
    {
        if ($name && $name != null && $name != '') {
            $originalFilename = $name;
        } else {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        }
        $safeFilename = $this->slugger->slug($originalFilename).'-'.uniqid();
        $file->setName($originalFilename);
        $file->setOriginalFilename($safeFilename);
        $file->setExt($uploadedFile->guessExtension());
        $fileName = $file->getOriginalFilename().'.'.$file->getExt();
        try {
            if ($private) {
                $file->setPath($this->privateDirectory.'/'.$this->privateUploadPath);
                $uploadedFile->move($this->getFullPrivateUploadPath(), $fileName);
            } else {
                $file->setPath($this->publicUploadPath);
                $uploadedFile->move($this->getFullPublicUploadPath() , $fileName);
            }
        } catch (FileException $e) {
            return $e;
        }
        return $file;
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
 
    public function getUrl(?string $fileName, bool $private = false, bool $absolute = true)
    {
        if (empty($fileName)) return null;
 
        if ($absolute) { 
            if ($private) {
                return $this->urlHelper->getAbsoluteUrl($this->absolutePrivatePath.'/'.$this->privateUploadPath.'/'.$fileName);
            }
            return $this->urlHelper->getAbsoluteUrl($this->publicPath.'/'.$this->publicUploadPath.'/'.$fileName);
        }
        if ($private) {
            return $this->urlHelper->getRelativePath($this->absolutePrivatePath.'/'.$this->privateUploadPath.'/'.$fileName);
        }
        return $this->urlHelper->getRelativePath($this->publicPath.'/'.$this->publicUploadPath.'/'.$fileName);
    }
}
