<?php

namespace App\Core\Services;

use App\Core\Repository\MenuRepository;

class MenuLoader
{
    /** @var MenuRepository */
    private $menuRepo;
    
    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function getMainMenu() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true]);
        return $menu;
    }
    public function getPublishedMainMenu() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true, 'isPublished' => true]);
        return $menu;
    }
    public function getMenuWithPosition($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position]);
        return $menu;
    }
    public function getPublishedMenuWithPosition($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position, 'isPublished' => true]);
        return $menu;
    }
    public function getMenuByName($name) {
        $menu = $this->menuRepo->findOneBy(['name' => $name]);
        return $menu;
    }
    public function getPublishedMenuByName($name) {
        $menu = $this->menuRepo->findOneBy(['position' => $name, 'isPublished' => true]);
        return $menu;
    }
    public function getMenuById($name) {
        $menu = $this->menuRepo->findOneBy(['id' => $id]);
        return $menu;
    }
    public function getPublishedMenuById($id) {
        $menu = $this->menuRepo->findOneBy(['position' => $id, 'isPublished' => true]);
        return $menu;
    }
}
