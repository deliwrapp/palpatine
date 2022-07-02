<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File as FileValidator;
use App\Core\Entity\File;

class ModuleUploadFormType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     
        $builder
            ->add('upload_module', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'error_bubbling' => true,
                'constraints' => [
                    new FileValidator([
                        /* 'maxSize' => '4096k', */ 
                        'mimeTypes' => 'application/zip',
                        'mimeTypesMessage' => "This file isn't valid. Only ZipFile accepted.",
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'install',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'module_item',
        ]);
    }
}