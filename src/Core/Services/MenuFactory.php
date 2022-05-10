<?php

namespace App\Core\Services;

use App\Core\Entity\Menu;
use App\Core\Repository\MenuRepository;

class MenuFactory
{
   
    /** @var MenuRepository */
    private $menuRepo;
    
    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function createMenu() {
        return $menu = new Menu;
    }

    public function createDefaultMenu() {
        $menu = new Menu;
        $menu->setName('default-menu');
        $menu->setIsMainMenu(true);
        $menu->setIsPublished(true);
        $this->menuRepo->add($menu);
        return $menu;
    }

}
