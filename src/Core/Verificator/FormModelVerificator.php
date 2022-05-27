<?php

namespace App\Core\Verificator;

use App\Core\Repository\FormModelRepository;
use App\Core\Entity\FormModel;

class FormModelVerificator
{
   
    /** @var FormModelRepository */
    private $formRepo;
    
    public function __construct(FormModelRepository $formRepo)
    {
        $this->formRepo = $formRepo;
    }

    
    /**
     * Test if FormModel exists and return it or a null value
     *
     * @param string $formModel
     * @return FormModel|null $formModel
     */
    public function checkIfFormModelExists(string $name) {
        $formModel = $this->formRepo->findOneBy(['name' => $name]);
        return $formModel;
    }

}
