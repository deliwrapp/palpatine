<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityRepository;
use App\Core\Entity\FormModel;
use App\Core\Entity\Template;

class FormModelFormType extends AbstractType
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
            ->add('sendTo', EmailType::class)
            ->add('formTemplate', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.type = :type')
                        ->setParameter('type', "form")
                        ->orderBy('t.name', 'ASC');
                }
            ])
            ->add('mailTemplate', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.type = :type')
                        ->setParameter('type', "email")
                        ->orderBy('t.name', 'ASC');
                }
            ])
            ->add('isPublished', CheckboxType::class, [
                'required'   => false
            ])
            ->add('locale', ChoiceType::class, [
                'required'   => false,
                'choices'  => array_flip($localesList)
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edit',
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormModel::class,
            'submitBtn' => 'Validate',
            'mode' => 'edition',
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'form_model_item',
        ]);
    }
}