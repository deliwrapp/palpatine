<?php

namespace App\Core\Verificator;

use App\Core\Repository\MenuRepository;
use App\Core\Entity\Menu;

class MenuVerificator
{
   
    /** @var MenuRepository */
    private $menuRepo;
    
    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    
    /**
     * Test if main menu exists and return it or a null value
     *
     * @return Menu|null $menu
     */
    public function checkIfMainMenuExists() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true]);
        return $menu;
    }

    /**
     * Test if menu with a defined position exists and return it or a null value
     *
     * @return Menu|null $menu
     */
    public function checkIfMenuWithPositionExists($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position]);
        return $menu;
    }

}
