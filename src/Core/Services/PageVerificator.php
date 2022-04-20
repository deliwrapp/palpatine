<?php

namespace App\Core\Services;

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

    public function checkIfPageGroupAndLocaleExists($pageGroupId, $locale) {
        $pageTest = $this->pageRepo->findOneByPageGroupAndLocale($pageGroupId, $locale);
        return $pageTest = $pageTest ? true : false;
    }

    public function checkIfIsHomepageAndLocaleExists($pageGroupId, $locale) {
        $pageTest = $this->pageRepo->findOneByIsHomepageAndLocale(true, $locale);
        return $pageTest = $pageTest ? true : false;
    }

}
