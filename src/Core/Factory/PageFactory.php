<?php

namespace App\Core\Factory;

use Doctrine\Persistence\ManagerRegistry;
use App\Core\Repository\PageRepository;
use App\Core\Repository\PageBlockRepository;
use App\Core\Repository\BlockRepository;
use App\Core\Verificator\PageVerificator;
use App\Core\Entity\Page;
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

    /**
     * Init a new page
     * 
     * @param Page $page
     * @param bool $record = false
     * @return Page|string $page
     */
    public function initPage(Page $page, $record = false) {
        if ($page->getIsHomepage()) {
            $testHomepage = $this->pageVerif->checkIfIsHomepageAndLocaleExists($page->getLocale());
            if ($testHomepage) {
                return 'ERROR :  - Duplicate Homepage with same Locale - ';
            }
        }
        $page->setUrl($this->urlConverter($page->getname()));
        $page->setPageGroupId($this->pageGroupeInit());
        $page = $this->createFullPath($page);
        if ($record) {
            $this->recordPage($page);
        }
        return $page;
    }

    /**
     * Duplicate a page
     * 
     * @param Page $page
     * @param string $name
     * @param string $locale,
     * @param bool $record = false
     * @return Page $page
     * @return string $page (to handle error with a message)
     */
    public function duplicatePage(Page $page, string $name, string $locale, $record = false) {
        $newPage = false;
        $testHomepage = false;
        $testGroupAndLocale = $this->pageVerif->checkIfPageGroupAndLocaleExists($page->getPageGroupId(), $locale);
        if ($page->getIsHomepage()) {
            $testHomepage = $this->pageVerif->checkIfIsHomepageAndLocaleExists($locale);
        }
        
        if (!$testGroupAndLocale && !$testHomepage) {
            $newPage = new Page();
            $newPage = $page->duplicate($newPage);
            $newPage->setName($name);
            $newPage->setLocale($locale);
            $newPage->setUrl($this->urlConverter($name));
            $newPage = $this->createFullPath($newPage);
            if ($page->getBlocks()) {
                foreach ($page->getBlocks() as $pageBlock) {
                    $newPageBlock = new PageBlock;
                    $newPageBlock = $pageBlock->duplicate($newPageBlock, true);
                    $newPage->addBlock($newPageBlock);
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

    /**
     * Record a page in database
     * 
     * @param Page $page
     * @return void
     */
    public function recordPage(Page $page):void {
        $this->pageRepo->add($page);
    }

    /**
     * Url converter for clean url page
     * 
     * @param string $string
     * @return string $string
     */
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

    /**
     * Page Group Id for localization page group
     * 
     * @param string $string
     * @return string $string
     */
    public function pageGroupeInit(): string {
        $randomNbr = random_int(12346, 9876543);
        return uniqid('page_group_'.$randomNbr.'_', true);
    }

    /**
     * Full Path Manager for page path
     * 
     * @param Page $page
     * @return Page $page
     */
    public function createFullPath(Page $page): Page {
        $fullPath = $page->getPrefix().'/'.$page->getUrl();
        $page->setFullPath($fullPath);
        return $page;
    }

    /**
     * Page re-order position handler
     * 
     * @param Page $page
     * @return Page $page
     */
    public function reOrderPageBlock(Page $page): Page {
        $blocks = $page->getBlocks();
        $blocks = $blocks->toArray();
        $blocksPosition = 1;
        usort($blocks, function($a, $b) {return strcmp($a->getPosition(), $b->getPosition());});
        foreach ($blocks as $block) {
            $block->setPosition($blocksPosition);
            $blocksPosition = $blocksPosition + 1;
        }
        $this->blockRepo->flush();
        return $page;
    }
}
