<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Core\Entity\FormModelField;

class FormModelFieldFormType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     
        $builder
            ->add('label', TextType::class)
            ->add('position', NumberType::class)
            ->add('defaultValue', TextType::class, [
                'required'   => false
            ])
            ->add('placeholder', TextType::class, [
                'required'   => false
            ])
            ->add('required', CheckboxType::class, [
                'required'   => false
            ])
            ->add('options', TextType::class, [
                'required'   => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edit',
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormModelField::class,
            'submitBtn' => 'Validate',
            'mode' => 'edition',
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'form_mode_fileld_item',
        ]);
    }
}