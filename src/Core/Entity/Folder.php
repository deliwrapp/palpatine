<?php

namespace App\Core\Entity;

use App\Core\Repository\FolderRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=FolderRepository::class) 
 * @HasLifecycleCallbacks
 */
class Folder implements ArrayAccess
{
    /**
     * Construct Folder
     *
     * @param bool $isPublished = false
     * @param bool $private = true
     * @return Folder
     */
    public function __construct(bool $isPublished = false, bool $private = true)
    {
        $this->isPublished = $isPublished;
        $this->private = $private;
        $this->subFolders = new ArrayCollection();
        $this->files = new ArrayCollection();
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Folder", inversedBy="subFolders")
     */
    private $folder;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Folder", mappedBy="folder", cascade={"persist", "remove"})
     */
    private $subFolders;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\File", mappedBy="folder", cascade={"persist"})
     */
    private $files;

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

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }
    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    
    /**
     * @return Collection|Folder[]
     */
    public function getSubFolders(): Collection
    {
        return $this->subFolders;
    }
    /**
     * @return ArrayCollection|Folder[]
     */
    public function getSubFoldersArray()
    {
        return $this->subFolders;
    }
    /**
     * @param Folder $subFolder
     */
    public function addSubFolder(File $subFolder): void
    {
        if (!$this->subFolders->contains($subFolder)) {
            $this->subFolders[] = $subFolder;
            $subFolder->setFolder($this);
        }
    }
    /**
     * @param Folder $subFolder
     */
    public function removeSubFolder(Folder $subFolder)
    {
        if (!$this->subFolders->contains($subFolder)) {
            return;
        }
        $this->subFolders->removeElement($subFolder);
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }
    /**
     * @return ArrayCollection|File[]
     */
    public function getFilesArray()
    {
        return $this->files;
    }
    /**
     * @param File $file
     */
    public function addFile(File $file): void
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setFolder($this);
        }
    }
    /**
     * @param Files $file
     */
    public function removeFile(File $file)
    {
        if (!$this->files->contains($file)) {
            return;
        }
        $this->files->removeElement($file);
    }
    
    public function getFolderPath()
    {
        return $this->getOriginalFilename();
    }

    public function duplicate(File $file): File
    {
        $file->setName($this->name);
        $file->setOriginalFilename($this->originalFilename);
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
