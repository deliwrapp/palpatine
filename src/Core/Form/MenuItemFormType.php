<?php

namespace App\Core\Form;

use App\Core\Entity\MenuItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MenuItemFormType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     
        switch ($options['mode']) {
            case 'external-link':
                $builder
                    ->add('path', TextType::class, [
                        'required'   => true
                    ]);
                break;
            case 'internal-link':
                $builder
                    ->add('path', ChoiceType::class, [
                        'required' => true,
                        'choices'  => [
                          'homepage' => 'homepage'
                        ],
                    ]);
                break;
        }
        $builder
            ->add('name', TextType::class)
            ->add('customId', TextType::class, [
                'required'   => false
            ])
            ->add('customClass', TextType::class, [
                'required'   => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MenuItem::class,
            'submitBtn' => 'Validate',
            'mode' => 'internal-link',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'menu_item_item',
        ]);
    }
}