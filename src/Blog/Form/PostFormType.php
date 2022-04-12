<?php

namespace App\Blog\Form;

use App\Blog\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('isPublished')
            ->add('content', TextareaType::class, [
                'required'   => false
            ])
            ->add('link', TextType::class, [
                'required'   => false
            ])
            ->add('linkText', TextType::class, [
                'required'   => false
            ])
            ->add('blockTemplate', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'oldway' => 'blocks/post/oldway_show_post.html.twig',
                ],
            ]);

        /* $builder->get('blockTemplate')
        ->addModelTransformer(new CallbackTransformer(
            function ($tplArray) {
                // transform the array to a string
                return count($tplArray)? $tplArray[0]: null;
            },
            function ($tplString) {
                // transform the string back to an array
                return [$tplString];
            }
        )); */
            
        if ($options) {
            $builder
                ->add('submit', SubmitType::class, [
                    'label' => $options['change'],
                ]);
            if($options['tplOptions']) {
                
                $builder
                    ->add('blockTemplate', ChoiceType::class, [
                        'required' => false,
                        'multiple' => false,
                        'expanded' => false,
                        'choices'  => array_flip($options['tplOptions']),
                        'empty_data' => '',
                    ]);
            } else {
                $builder
                    ->add('blockTemplate', ChoiceType::class, [
                        'required' => false,
                        'multiple' => false,
                        'expanded' => false,
                        'choices'  => [
                            'oldway' => 'blocks/post/oldway_show_post.html.twig',
                        ],
                        'empty_data' => '',
                    ]);
            }
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
            'data_class' => Post::class,
            'change' => 'Validate',
            'tplOptions' => ['oldway' => 'blocks/post/oldway_show_post.html.twig'],
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'post_item',
        ]);
    }
}