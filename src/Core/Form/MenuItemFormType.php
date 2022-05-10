<?php

namespace App\Core\Form;

use App\Core\Entity\MenuItem;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MenuItemFormType extends AbstractType
{
    /** @var ParameterBagInterface */
    private $params;

    /** @var RouterInterface */
    private $router;

    /** @var PageRepository */
    private $pageRepo;
   
    public function __construct(
        ParameterBagInterface $params,
        RouterInterface $router,
        PageRepository $pageRepo
    ) {
        $this->params = $params;
        $this->router = $router;
        $this->pageRepo = $pageRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {    
        switch ($options['mode']) {
            case 'external':
                $builder
                    ->add('path', TextType::class, [
                        'required'   => true
                    ]);
                break;
            case 'internal':
                $routeList = [];
                $routeCollection = $this->router->getRouteCollection();
                foreach ($routeCollection->all() as $key => $value)
                {
                    $routeList[$key] = $key;
                }
                $builder
                    ->add('path', ChoiceType::class, [
                        'required' => true,
                        'choices'  => $routeList,
                    ]);
                break;    
            default :
                $pageList = [];
                $pages = $this->pageRepo->findBy(['isPublished' => true]);
                foreach ($pages as $page)
                {
                    $pageList[$page->getName()] = $page->getUrl();
                }
                $builder
                    ->add('path', ChoiceType::class, [
                        'required' => true,
                        'choices'  => $pageList
                    ]);
                break;
        }
        $userRoles = $this->params->get('userRoles');
        $builder
            ->add('name', TextType::class)
            ->add('customId', TextType::class, [
                'required'   => false
            ])
            ->add('customClass', TextType::class, [
                'required'   => false
            ])
            ->add('target', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'Self' => '_self',
                  'Blanck' => '_blank',
                  'Parent ' => '_parent ',
                  'Top' => '_top ',
                ],
            ])
            ->add('roleAccess', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'choices'  => $userRoles,
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
            'mode' => 'internal',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'menu_item_item',
        ]);
    }
}