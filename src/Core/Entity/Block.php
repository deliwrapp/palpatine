<?php

namespace App\Core\Entity;

use App\Core\Repository\BlockRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=BlockRepository::class) 
 * @HasLifecycleCallbacks
 */
class Block implements ArrayAccess
{
    public function __construct()
    {
        $this->singleResult = true;
        $this->isPublished = false;
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
    private $query;

    /**
     * @ORM\Column(type="boolean")
     */
    private $singleResult;

    /**
     * @ORM\Column(type="string")
     */
    private $blockTemplate;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $locale;
    
    private $data;

    
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

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getSingleResult(): ?bool
    {
        return $this->singleResult;
    }
    public function setSingleResult(?bool $singleResult): self
    {
        $this->singleResult = $singleResult;

        return $this;
    }

    public function getBlockTemplate(): ?string
    {
        return $this->blockTemplate;
    }

    public function setBlockTemplate(?string $blockTemplate): self
    {
        $this->blockTemplate = $blockTemplate;

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

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function duplicate(Block $block): Block
    {
        $block->setName($this->name);
        $block->setLocale($this->locale);
        $block->setQuery($this->query);
        $block->setSingleResult($this->singleResult);
        $block->setBlockTemplate($this->blockTemplate);
        return $block;
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
