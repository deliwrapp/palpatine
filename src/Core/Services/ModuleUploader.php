<?php

// src/Core/Services/FileUploader.php
namespace App\Core\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
 
class ModuleUploader
{
    /** @var string */
    private $privateUploadPath;

    /** @var string */
    private $absolutePrivatePath;

    /** @var ParameterBagInterface */
    private $params;
 
    public function __construct(
        ParameterBagInterface $params
    )
    {
        $this->privateUploadPath = $params->get('private_uploads_directory');
        $this->absolutePrivatePath = $params->get('absolute_private_directory');
    }
 
    /**
     * Upload a new File
     *
     * @param UploadedFile $uploadedZipModule
     * @return string $uploadedZipPath
     * @return FileException $e
     */
    public function upload(UploadedFile $uploadedZipModule)
    {
        try {
            $ext = $uploadedZipModule->guessExtension();
            $uploadedZipModule->move($this->getFullPrivateUploadPath(), 'module-to-install.'.$ext);
            return $this->getFullPrivateUploadPath().'/module-to-install.'.$ext;
        } catch (FileException $e) {
            throw $e;
        }
    }

    public function getFullPrivateUploadPath()
    {
        return $this->absolutePrivatePath.'/'.$this->privateUploadPath;
    }
}
