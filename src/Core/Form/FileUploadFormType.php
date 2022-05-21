<?php

namespace App\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraints\File as FileValidator;
use App\Core\Entity\File;

class FileUploadFormType extends AbstractType
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
        
        $userRoles = $this->params->get('userRoles');
        switch ($options['mode']) {
            case 'upload':
                $builder
                    ->add('name', TextType::class, [
                        'required'   => false
                    ]) 
                    ->add('isPublished', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('private', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('roleAccess', ChoiceType::class, [
                        'required' => false,
                        'multiple' => false,
                        'expanded' => false,
                        'choices'  => $userRoles,
                    ])
                    ->add('description', TextareaType::class, [
                        'required'   => false
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Upload',
                    ]);
                break;
            case 'edit':
                $builder
                    ->add('isPublished', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('roleAccess', ChoiceType::class, [
                        'required' => false,
                        'multiple' => false,
                        'expanded' => false,
                        'choices'  => $userRoles,
                    ])
                    ->add('description', TextareaType::class, [
                        'required'   => false
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit',
                    ]);
                break;
            case 'edit-name':
                $builder
                    ->add('name', TextType::class)
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit name',
                    ]);
                break;
            case 'edit-private':
                $builder
                    ->add('private', CheckboxType::class, [
                        'required'   => false
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Edit Access',
                    ]);
                break;
        }
        
        switch ($options['file_type']) {
            case 'custom':
                $builder
                    ->add('upload_file', FileType::class, [
                        'label' => false,
                        'mapped' => false, // Tell that there is no Entity to link
                        'required' => true,
                        'constraints' => [
                            new FileValidator([
                                'maxSize' => '4096k', 
                                'mimeTypes' => $options['mime_types'],
                                'mimeTypesMessage' => $options['mime_types_message'],
                            ])
                        ],
                    ]);
                break;
            case 'img':
                $builder
                    ->add('upload_file', FileType::class, [
                        'label' => false,
                        'mapped' => false, // Tell that there is no Entity to link
                        'required' => true,
                        'constraints' => [
                            new FileValidator([
                                'maxSize' => '4096k', 
                                'mimeTypes' => [ // We want to let upload only jpg, png or gif
                                    'text/x-comma-separated-values',
                                ],
                                'mimeTypesMessage' => "This image isn't valid.",
                            ])
                        ],
                    ]);
                break;
            case 'media':
                $builder
                    ->add('upload_file', FileType::class, [
                        'label' => false,
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new FileValidator([
                                'maxSize' => '4096k',
                                'mimeTypes' => [ // We want to let upload only mp3, mp4
                                    'text/x-comma-separated-values', 
                                ],
                                'mimeTypesMessage' => "This media isn't valid.",
                            ])
                        ],
                    ]);
                break;
            default:
                $builder
                    ->add('upload_file', FileType::class, [
                        'label' => false,
                        'mapped' => false,
                        'required' => true,
                        'constraints' => [
                            new FileValidator([
                                'maxSize' => '4096k',
                            ])
                        ],
                    ]);
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => File::class,
            'submitBtn' => 'Validate',
            'mode' => 'edition',
            'file_type' => 'default',
            'mime_types' => [],
            'mime_types_message' => 'This image isn\'t valid.',
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'file_item',
        ]);
    }
}