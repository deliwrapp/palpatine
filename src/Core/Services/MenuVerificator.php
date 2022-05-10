<?php

namespace App\Core\Services;

use App\Core\Repository\MenuRepository;

class MenuVerificator
{
   
    /** @var MenuRepository */
    private $menuRepo;
    
    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function checkIfMainMenuExists() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true]);
        return $menu;
    }
    public function checkIfMenuWithPositionExists($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position]);
        return $menu;
    }

}
