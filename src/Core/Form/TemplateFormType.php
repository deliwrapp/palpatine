<?php

namespace App\Core\Form;

use App\Core\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TemplateFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('templatePath', TextType::class)
            ->add('cssLink', TextType::class, [
                'required'   => false
            ])
            ->add('scriptLink', TextType::class, [
                'required'   => false
            ])
            ->add('isPublished', CheckboxType::class, [
                'required'   => false
            ]);
            
        if ($options) {
            $builder
                ->add('submit', SubmitType::class, [
                    'label' => $options['submitBtn'],
                ]);
        } else {
            $builder
                ->add('submit', SubmitType::class, [
                    'label' => 'Validate'
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
            'submitBtn' => 'Validate',
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'Template_item',
        ]);
    }
}