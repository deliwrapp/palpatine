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
     */
    private $block;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $position;
    

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

    public function getBlock():Block
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

}
