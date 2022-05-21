<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Core\Model\PageDuplication;

class PageDuplicationFormType extends AbstractType
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
            ->add('locale', ChoiceType::class, [
                'choices'  => array_flip($localesList)
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Duplicate'
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PageDuplication::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'page_item',
        ]);
    }
}