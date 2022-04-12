<?php

namespace App\Core\Entity;

use App\Core\Repository\PageRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use App\Core\Entity\PageBlock;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class) 
 * @HasLifecycleCallbacks
 */
class Page implements ArrayAccess
{
    public function __construct()
    {
        $this->isPublished = false;
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
     * @ORM\OneToMany(targetEntity="App\Core\Entity\PageBlock", mappedBy="page")
     */
    private $blocks;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;
    
    
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

    /**
     * @return Collection|PageBlock[]
     */
    public function getBlocks(): Collection
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
