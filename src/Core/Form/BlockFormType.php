<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Entity\Block;
use App\Core\Entity\Template;

class BlockFormType extends AbstractType
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
        $localesList = $this->params->get('appLocalesList');
        $builder
            ->add('name', TextType::class)
            ->add('content', TextareaType::class, [
                'required'   => false
            ])
            ->add('blockTemplate', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name',
                //'default_value' => 'block/default/default.html.twig',
            ])
            ->add('query', TextType::class, [
                'required'   => false
            ])
            ->add('singleResult', CheckboxType::class, [
                'required'   => false
            ])
            ->add('isPublished', CheckboxType::class, [
                'required'   => false
            ])
            ->add('locale', ChoiceType::class, [
                'choices'  => array_flip($localesList)
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
            'data_class' => Block::class,
            'submitBtn' => 'Validate',
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'block_item',
        ]);
    }
}