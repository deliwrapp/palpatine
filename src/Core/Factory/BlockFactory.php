<?php

namespace App\Core\Factory;

use Symfony\Component\String\Slugger\SluggerInterface;
use App\Core\Repository\PageBlockRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Verificator\PageVerificator;
use App\Core\Entity\Page;
use App\Core\Entity\PageBlock;

class BlockFactory
{

    /** @var PageBlockRepository */
    private $pageBlockRepo;

    /** @var BlockRepository */
    private $blockRepo;
    
    /** @var SluggerInterface */
    private $slugger;


    public function __construct(
        PageBlockRepository $pageBlockRepo, 
        BlockRepository $blockRepo,
        SluggerInterface $slugger
    )
    {
        $this->pageBlockRepo = $pageBlockRepo;
        $this->blockRepo = $blockRepo;
        $this->slugger = $slugger;
    }

    /**
     * Init options array for a block
     * 
     * @param array $options
     * @return Page|string $page
     */
    public function setOptionsData(array $options) {
        $convertedOptionsArray = [];
        $i = 0;
        foreach ($options as $opt) {
            if (isset($opt['name'])) {
                $sluggedName = $this->slugger->slug($opt['name'], '_');
                $convertedOptionsArray[strtolower($sluggedName->toString())] = $opt;
            } else {
                $convertedOptionsArray[$i] = $opt;
            }
            $i++;
        }
        return $convertedOptionsArray;
    }

    /**
     * Duplicate a block
     * 
     * @param Block $block
     * @param bool $record = false
     * @return Block $newBlock
     */
    public function duplicateBlock(Block $block, $record = false) {
        $newBlock = new Block();
        $newBlock = $block->duplicate($newBlock);
        if ($record) {
            $this->recordBlock($newPage);
        }
        return $newBlock;
    }

    /**
     * Duplicate a block
     * 
     * @param PageBlock $pageBlock
     * @param bool $record = false
     * @return Block $newBlock
     */
    public function duplicatePageBlock(PageBlock $pageBlock, $record = false) {
        $newPageBlock = new PageBlock();
        $newPageBlock = $pageBlock->duplicate($newPageBlock);
        if ($record) {
            $this->recordPageBlock($newPageBlock);
        }
        return $newBlock;
    }

    /**
     * Record a block in database
     * 
     * @param Block $block
     * @return void
     */
    public function recordBlock(Block $block):void {
        $this->blockRepo->add($block);
    }

    /**
     * Record a pageBlock in database
     * 
     * @param PageBlock $pageBlock
     * @return void
     */
    public function recordPageBlock(PageBlock $pageBlock):void {
        $this->pageBlockRepo->add($pageBlock);
    }
}
