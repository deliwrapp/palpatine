<?php

namespace App\Core\Entity;

use App\Core\Repository\PageBlockRepository;
use App\Core\Entity\Block;
use App\Core\Entity\Page;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageBlockRepository::class)
 */
class PageBlock
{
    public function __construct()
    {
        $this->isPublished = false;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Page", inversedBy="blocks")
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Block")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id", nullable=true)
     */
    private $block;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $position;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage():Page
    {
        return $this->page;
    }

    public function setPage(Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getBlock()
    {
        return $this->block;
    }

    public function setBlock(Block $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setContentToNull(): self
    {
        $this->content = null;

        return $this;
    }

}
