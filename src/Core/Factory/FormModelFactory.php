<?php

namespace App\Core\Factory;

use App\Core\Entity\FormModel;
use App\Core\Entity\FormModelField;
use App\Core\Repository\FormModelRepository;
use App\Core\Repository\FormModelFieldRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FormModelFactory
{
   
    /** @var FormModelRepository */
    private $formRepo;

    /** @var FormModelFieldRepository */
    private $formFieldRepo;

    /** @var ParameterBagInterface */
    private $params;

    /** @var string */
    private $defaultLocale;
    
    public function __construct(
        FormModelRepository $formRepo,
        FormModelFieldRepository $formFieldRepo,
        ParameterBagInterface $params
    )
    {
        $this->formRepo = $formRepo;
        $this->formFieldRepo = $formFieldRepo;
        $this->params = $params;
        $this->defaultLocale = $this->params->get('locale');
    }

    /**
     * Create a new Form model
     *
     * @return FormModel $formModel
     */
    public function createFormModel() {
        return $formModel = new FormModel;
    }

    /**
     * Create a new Form model Field
     * @return FormModelField $formModelField
     */
    public function createFormField() {
        $formModelField = new FormModelField;
        return $formModelField;
    }

    /**
     * Create a default FormModel
     * 
     * @var string|null $locale = null
     * @return FormModel $formModel
     */
    public function createDefaultFormModel($locale = null) {
        if ($locale == null) {
            $locale = $this->defaultLocale;
        }
        $formModel = new FormModel;
        $formModel->setName('default-form');
        $formModel->setIsPublished(true);
        $formModel->setLocale($locale);
        return $formModel;
    }

    /**
     * Create a default FormModel
     * 
     * @var FormModel $formModel
     * @var string $type = "text"
     * @return FormModel $formModel
     */
    public function addField(FormModel $formModel, $type = "text") {
        $formModelField = new FormModelField;
        $formModelField->setLabel('field-'.$type);
        $formModelField->setType($type);
        $formModel->addField($formModelField);
        $this->formFieldRepo->add($formModelField);
        $this->formRepo->flush();
        return $formModel;
    }

    /**
     * Create a default FormModel
     * 
     * @var FormModel $formModel
     * @var FormModelField $formModelField
     * @return FormModel $formModel
     */
    public function removeField(FormModel $formModel, $formModelField) {
        $formModel->removeField($formModelField);
        $this->formFieldRepo->remove($formModelField);
        $this->formFieldRepo->flush();
        return $formModel;
    }

}
