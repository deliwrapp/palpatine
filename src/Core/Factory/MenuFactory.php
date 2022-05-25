<?php

namespace App\Core\Factory;

use App\Core\Entity\Menu;
use App\Core\Repository\MenuRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MenuFactory
{
   
    /** @var MenuRepository */
    private $menuRepo;

    /** @var ParameterBagInterface */
    private $params;

    /** @var string */
    private $defaultLocale;
    
    public function __construct(
        MenuRepository $menuRepo,
        ParameterBagInterface $params
    )
    {
        $this->menuRepo = $menuRepo;
        $this->params = $params;
        $this->defaultLocale = $this->params->get('locale');
    }

    /**
     * Create a new Menu
     *
     * @return Menu $menu
     */
    public function createMenu() {
        return $menu = new Menu;
    }

    /**
     * Create a default Menu
     * 
     * @var string|null $locale = null
     * @return Menu $menu
     */
    public function createDefaultMenu($locale = null) {
        if ($locale == null) {
            $locale = $this->defaultLocale;
        }
        $menu = new Menu;
        $menu->setName('default-menu');
        $menu->setIsMainMenu(true);
        $menu->setIsPublished(true);
        $menu->setLocale($locale);
        $this->menuRepo->add($menu);
        return $menu;
    }

}
