<?php

namespace App\Core\Entity;

use App\Core\Repository\FileRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class) 
 * @HasLifecycleCallbacks
 */
class File implements ArrayAccess
{
    public function __construct()
    {
        
        $this->isPublished = false;
        $this->private = true;
    }

    use SoftEditionTrackingTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext;

    /**
     * @ORM\Column(type="string")
     */
    private $path;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $private;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $roleAccess;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }
    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }
    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }
    
    public function getPath(): ?string
    {
        return $this->path;
    }
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPrivate(): ?bool
    {
        return $this->private;
    }
    public function setPrivate(?bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }
    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getRoleAccess(): ?string
    {
        return $this->roleAccess;
    }
    public function setRoleAccess(?string $roleAccess): self
    {
        $this->roleAccess = $roleAccess;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFilePath()
    {
        return $this->getPath().'/'.$this->getOriginalFilename().'.'.$this->getExt();
    }

    public function duplicate(File $file): File
    {
        $file->setName($this->name);
        $file->setOriginalFilename($this->originalFilename);
        $file->setExt($this->ext);
        $file->setPath($this->path);
        $file->setPrivate($this->private);
        $file->setIsPublished($this->isPublished);
        $file->setRoleAccess($this->roleAccess);
        $file->setDescription($this->description);
        return $file;
    }

    public function offsetExists($offset) {
        $value = $this->{"get$offset"}();
        return $value !== null;
    }

    public function offsetSet($offset, $value) {
        $this->{"set$offset"}($value);
    }

    public function offsetGet($offset) {
        return $this->{"get$offset"}();
    }

    public function offsetUnset($offset) {
        $this->{"set$offset"}(null);
    }

}
