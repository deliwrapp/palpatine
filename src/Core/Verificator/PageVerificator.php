<?php

namespace App\Core\Verificator;

use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;

class PageVerificator
{
   
    /** @var PageRepository */
    private $pageRepo;
    
    public function __construct(PageRepository $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    /**
     * Test If Page with defined PageGroupId And Locale Exists and return boolean value
     * 
     * @param string $pageGroupId
     * @param string $locale
     * @return bool $pageTest
     */
    public function checkIfPageGroupAndLocaleExists(string $pageGroupId, string $locale) {
        $pageTest = $this->pageRepo->findOneByPageGroupAndLocale($pageGroupId, $locale);
        return $pageTest = $pageTest ? true : false;
    }

    /**
     * Test If Page defined as Homepage And with defined Locale Exists and return boolean value
     * 
     * @param string $pageGroupId
     * @param string $locale
     * @return bool $pageTest
     */
    public function checkIfIsHomepageAndLocaleExists($locale) {
        $pageTest = $this->pageRepo->findOneByIsHomepageAndLocale(true, $locale);
        return $pageTest = $pageTest ? true : false;
    }

}
