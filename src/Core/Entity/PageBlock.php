<?php

namespace App\Core\Entity;

use App\Core\Repository\PageBlockRepository;
use App\Core\Entity\Page;
use App\Core\Entity\Block;
use App\Core\Entity\Template;
use App\Core\Entity\File;
use App\Core\Entity\FormModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageBlockRepository::class)
 */
class PageBlock
{
    public function __construct()
    {
        $this->singleResult = true;
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $query;

    /**
     * @ORM\Column(type="boolean")
     */
    private $singleResult;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Template")
     */
    private $blockTemplate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\File")
     */
    private $media;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\FormModel")
     */
    private $formModel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $position;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $options = [];

    private $data;
    
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

    public function getBlockTemplate(): ?Template
    {
        return $this->blockTemplate;
    }
    public function setBlockTemplate(?Template $blockTemplate): self
    {
        $this->blockTemplate = $blockTemplate;
        return $this;
    }

    public function getMedia(): ?File
    {
        return $this->media;
    }
    public function setMedia(?File $media): self
    {
        $this->media = $media;
        return $this;
    }

    public function getFormModel(): ?FormModel
    {
        return $this->formModel;
    }
    public function setFormModel(?FormModel $formModel): self
    {
        $this->formModel = $formModel;
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

    public function getOptions(): array
    {
        $options = $this->options;
        if (null == $options) {
            $options = [];
        }
        return $options;
    }
    public function setOptions(array $options): self
    {
        $this->options = $options;
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

    public function duplicate(PageBlock $pageBlock, $full = false): PageBlock
    {
        $pageBlock->setName($this->name);
        $pageBlock->setContent($this->content);
        $pageBlock->setQuery($this->query);
        $pageBlock->setSingleResult($this->singleResult);
        $pageBlock->setBlockTemplate($this->blockTemplate);
        $pageBlock->setMedia($this->media);
        $pageBlock->setOptions($this->options);
        if ($full) {
            $pageBlock->setPosition($this->position);
        }
        return $pageBlock;
    }

    public function generateBlockModel(Block $block): Block
    {
        $block->setName($this->name);
        $block->setContent($this->content);
        $block->setQuery($this->query);
        $block->setSingleResult($this->singleResult);
        $block->setLocale($this->page->getLocale());
        $block->setBlockTemplate($this->blockTemplate);
        $block->setMedia($this->media);
        $block->setOptions($this->options);
        return $block;
    }

}
