<?php

namespace App\Core\Form;

use App\Core\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MenuFormType extends AbstractType
{
    /** @var ParameterBagInterface */
    private $params;

    public function __construct(
        ParameterBagInterface $params
    )
    {
        $this->params = $params;
    }
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $menuPositionsOpts = $this->params->get('menuPositionsOpts');
        $builder
            ->add('name', TextType::class)
            ->add('position', ChoiceType::class, [
                'choices'  => $menuPositionsOpts,
                'required'   => false
            ])
            ->add('isMainMenu', CheckboxType::class, [
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
            'data_class' => Menu::class,
            'submitBtn' => 'Validate',
            'mode' => 'edition',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'menu_item',
        ]);
    }
}