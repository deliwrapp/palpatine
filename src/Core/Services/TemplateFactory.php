<?php

namespace App\Core\Services;

use App\Core\Entity\Template;
use App\Core\Repository\TemplateRepository;

class TemplateFactory
{
   
    /** @var TemplateRepository */
    private $tplRepo;
    
    public function __construct(TemplateRepository $tplRepo)
    {
        $this->tplRepo = $tplRepo;
    }

    public function createTemplate() {
        return $tpl = new Template;
    }

    public function createDefaultTemplate() {
        $tpl = new Template;
        $tpl->setName('default-block');
        $tpl->setTemplatePath('block/default/default.html.twig');
        $tpl->setCssLink('default-block-css');
        $tpl->setScriptLink('default-block-js');
        $tpl->setIsPublished(true);
        $this->tplRepo->add($tpl);
        return $tpl;
    }

    public function checkIfDefaultTemplateExists($defaultName = 'default-block') {
        $tpl = $this->tplRepo->findOneBy(['name' => $defaultName]);
        return $tpl;
    }

}
