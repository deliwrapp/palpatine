<?php

namespace App\Core\Form;

use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageFormType extends AbstractType
{
    /** @var PageRepository */
    private $pageRepo;

    /** @var ParameterBagInterface */
    private $params;
    
    public function __construct(
        PageRepository $pageRepo,
        ParameterBagInterface $params
    )
    {
        $this->pageRepo = $pageRepo;
        $this->params = $params;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     
        switch ($options['mode']) {
            case 'edition':
                $builder
                    ->add('name', TextType::class) 
                    ->add('isPublished', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('isHomepage', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit',
                    ]);
                break;
            case 'edit-locale':
                $localesList = $this->params->get('appLocalesList');
                $builder
                    ->add('locale', ChoiceType::class, [
                        'choices'  => array_flip($localesList)
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit Locale',
                    ]);
                break;
            case 'edit-url':
                $builder
                    ->add('url', TextType::class)
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit Url',
                    ]);
                break;
            case 'add-page-to-page-group':
                $builder
                    ->add('pageGroupId', ChoiceType::class, [
                        // looks for choices from this entity
                        'choices' => $this->pageRepo->getPages(),
                        'choice_value' => 'pageGroupId',
                        'choice_label' => 'name',
                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Add to Page Group',
                    ]);
                break;
            default:
                $builder
                    ->add('name', TextType::class) 
                    ->add('isPublished', CheckboxType::class)
                    ->add('isHomepage', CheckboxType::class)
                    ->add('locale', ChoiceType::class, [
                        'choices'  => [
                            'français' => 'fr',
                            'english' => 'en',
                            'portugues' => 'pt'
                        ]
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Validate'
                    ]);
                break;
        }  
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'submitBtn' => 'Validate',
            'mode' => 'edition',
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