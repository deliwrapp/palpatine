<?php

namespace App\Core\Factory;

use App\Core\Entity\FormModel;
use App\Core\Repository\FormModelRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FormModelFactory
{
   
    /** @var FormModelRepository */
    private $formRepo;

    /** @var ParameterBagInterface */
    private $params;

    /** @var string */
    private $defaultLocale;
    
    public function __construct(
        FormModelRepository $formRepo,
        ParameterBagInterface $params
    )
    {
        $this->formRepo = $formRepo;
        $this->params = $params;
        $this->defaultLocale = $this->params->get('locale');
    }

    /**
     * Create a new Menu
     *
     * @return FormModel $menu
     */
    public function createMenu() {
        return $formModel = new FormModel;
    }

    /**
     * Create a default FormModel
     * 
     * @var string|null $locale = null
     * @return FormModel $formModel
     */
    public function createDefaultMenu($locale = null) {
        if ($locale == null) {
            $locale = $this->defaultLocale;
        }
        $formModel = new FormModel;
        $formModel->setName('default-form');
        $formModel->setIsPublished(true);
        $formModel->setLocale($locale);
        return $formModel;
    }

}
