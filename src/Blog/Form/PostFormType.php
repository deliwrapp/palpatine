<?php

namespace App\Post\Form;

use App\Blog\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('isPublished')
            ->add('content', TextareaType::class, [
                'required'   => false
            ]);

            
        if ($options) {
            $builder
                ->add('submit', SubmitType::class, [
                    'label' => $options['change'],
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
            'data_class' => Post::class,
            'change' => 'Validate',
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