<?php

namespace App\Core\Entity;

use App\Core\Repository\PageRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use App\Core\Entity\PageBlock;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @UniqueEntity(fields="name", message="Name is already taken.")
 * @UniqueEntity(fields="url", message="URL is already taken.")
* @UniqueEntity(fields="fullPath", message="FullPath is already taken.")
 * @HasLifecycleCallbacks
 */
class Page implements ArrayAccess
{
    public function __construct()
    {
        $this->isPublished = false;
        $this->isHomepage = false;
        $this->blocks = new ArrayCollection();
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
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $prefix;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $fullPath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pageGroupId;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\PageBlock", mappedBy="page", cascade={"persist", "remove"})
     */
    private $blocks;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isHomepage;
    
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getFullPath(): ?string
    {
        return $this->fullPath;
    }

    public function setFullPath(string $fullPath): self
    {
        $this->fullPath = $fullPath;

        return $this;
    }
    
    public function getPageGroupId(): ?string
    {
        return $this->pageGroupId;
    }

    public function setPageGroupId(string $pageGroupId): self
    {
        $this->pageGroupId = $pageGroupId;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection|PageBlock[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * @return ArrayCollection|PageBlock[]
     */
    public function getBlocksArray()
    {
        return $this->blocks;
    }
    
    /**
     * @param PageBlock $pageBlock
     */
    public function addBlock(PageBlock $pageBlock): void
    {
        if (!$this->blocks->contains($pageBlock)) {
            $this->blocks[] = $pageBlock;
            $pageBlock->setPage($this);
        }
    }

    /**
     * @param PageBlock $pageBlock
     */
    public function removeBlock(PageBlock $pageBlock)
    {
        if (!$this->blocks->contains($pageBlock)) {
            return;
        }
        $this->blocks->removeElement($pageBlock);
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

    public function getIsHomepage(): ?bool
    {
        return $this->isHomepage;
    }
    public function setIsHomepage(?bool $isHomepage): self
    {
        $this->isHomepage = $isHomepage;

        return $this;
    }

    public function duplicate(Page $page): Page
    {
        $page->setName($this->name);
        $page->setUrl($this->url);
        if ($page->getPrefix()) {
            $page->setPrefix($this->prefix);
        }
        $page->setFullPath($this->fullPath);
        $page->setPageGroupId($this->pageGroupId);
        $page->setLocale($this->locale);
        $page->setIsHomepage($this->isHomepage);
        return $page;
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
