<?php

namespace App\Security\Form;

use App\Security\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserAccountFormType extends AbstractType
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
        switch ($options['mode']) {
            case 'edition':
                $builder
                    ->add('username', TextType::class)
                    ->add('locale', ChoiceType::class, [
                        'choices'  => array_flip($localesList)
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'cmd.edit',
                    ]);
                break;
            case 'edit-email':
                $builder
                    ->add('email', EmailType::class)
                    ->add('submit', SubmitType::class, [
                        'label' => 'cmd.edit',
                    ]);
                break;
            case 'edit-password':
                $builder
                    ->add('password', PasswordType::class, ['label' => 'form.password'])
                    ->add('submit', SubmitType::class, [
                        'label' => 'cmd.edit',
                    ]);
                break;
        } 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'mode' => 'edition',
        ]);
    }
}
