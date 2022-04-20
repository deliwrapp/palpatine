<?php

namespace App\Core\Services;

use Doctrine\Persistence\ManagerRegistry;
use App\Core\Repository\PageRepository;
use App\Core\Repository\PageBlockRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Services\PageVerificator;
use App\Core\Entity\Page;
use App\Core\Entity\Block;
use App\Core\Entity\PageBlock;

class PageFactory
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var PageBlockRepository */
    private $pageBlockRepo;

    /** @var BlockRepository */
    private $blockRepo;

    /** @var PageVerificator */
    private $pageVerif;

    /** @var ManagerRegistry */
    private $em;

    public function __construct(
        PageRepository $pageRepo,
        PageBlockRepository $pageBlockRepo, 
        BlockRepository $blockRepo, 
        PageVerificator $pageVerif,
        ManagerRegistry $em
    )
    {
        $this->pageRepo = $pageRepo;
        $this->pageBlockRepo = $pageBlockRepo;
        $this->blockRepo = $blockRepo;
        $this->pageVerif = $pageVerif;
        $this->em = $em->getManager();
    }

    public function initPage(Page $page, $record = false): Page {
        if ($page->getIsHomepage()) {
            $testHomepage = $this->pageVerif->checkIfIsHomepageAndLocaleExists(true, $page->getLocale());
            if ($testHomepage) {
                return 'ERROR :  - Duplicate Homepage with same Locale - ';
            }
        }
        $page->setUrl($this->urlConverter($page->getname()));
        $page->setPageGroupId($this->pageGroupeInit());
        if ($record) {
            $this->recordPage($page);
        }
        return $page;
    }

    public function duplicatePage(Page $page, string $name, string $locale, $record = false) {

        $newPage = false;
        $testHomepage = false;
        $testGroupAndLocale = $this->pageVerif->checkIfPageGroupAndLocaleExists($page->getPageGroupId(), $locale);
        if ($page->getIsHomepage()) {
            $testHomepage = $this->pageVerif->checkIfIsHomepageAndLocaleExists(true, $locale);
        }
        
        if (!$testGroupAndLocale && !$testHomepage) {
            $newPage = new Page();
            $newPage = $page->duplicate($newPage);
            $newPage->setName($name);
            $newPage->setLocale($locale);
            $newPage->setUrl($this->urlConverter($name));
            if ($page->getBlocks()) {
                foreach ($page->getBlocks() as $pageBlock) {
                    $newPageBlock = new PageBlock;
                    $block = $pageBlock->getBlock();
                    $newBlock = new Block();
                    $newBlock = $block->duplicate($block);
                    $newPageBlock->setBlock($newBlock);
                    $newPage->addBlock($newPageBlock);
                    $this->blockRepo->add($newBlock, false);
                    $this->pageBlockRepo->add($newPageBlock, false);
                }
            }
            if ($record) {
                $this->recordPage($newPage);
            }
        } else {
            $testGroupAndLocale = $testGroupAndLocale ? ' - Duplicate Locale in Page Group - ' : null;
            $testHomepage = $testHomepage ? ' - Duplicate Homepage with same Locale - ' : null;
            $newPage = 'ERROR : ' .$testGroupAndLocale. ' ' .$testHomepage;
        }
        return $newPage;
    }

    public function recordPage(Page $page):void {
        $this->pageRepo->add($page);
    }

    public function urlConverter(string $string): string {
        $string = htmlentities($string, ENT_NOQUOTES, 'utf-8');
        $string = trim($string);
        $string = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $string);
        $string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string); // pour les ligatures e.g. 'œ'
        $string = preg_replace('#&[^;]+;#', '-', $string); // supprime les autres caractères
        $string = strtolower($string);
        $string = str_replace([" ", "'" , ",", "?" , "!", ".", ";", ":", "/", "(", ")", "°"], "-", $string);       
        return $string;
    }

    public function pageGroupeInit(): string {
        $randomNbr = random_int(12346, 9876543);
        return uniqid('page_group_'.$randomNbr.'_', true);
    }
}
