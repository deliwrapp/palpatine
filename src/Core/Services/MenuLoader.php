<?php

namespace App\Core\Services;

use App\Core\Repository\MenuRepository;
use App\Core\Entity\Menu;

class MenuLoader
{
    /** @var MenuRepository */
    private $menuRepo;
    
    public function __construct(MenuRepository $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    /**
     * Get the Main Menu
     *
     * @return Menu|null $menu
     */
    public function getMainMenu() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true]);
        return $menu;
    }

    /**
     * Get the Published Main Menu
     *
     * @return Menu|null $menu
     */
    public function getPublishedMainMenu() {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true, 'isPublished' => true]);
        return $menu;
    }

    /**
     * Get a Menu defined by position
     *
     * @return Menu|null $menu
     */
    public function getMenuWithPosition($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position]);
        return $menu;
    }

    /**
     * Get a Published Menu defined by position
     *
     * @return Menu|null $menu
     */
    public function getPublishedMenuWithPosition($position) {
        $menu = $this->menuRepo->findOneBy(['position' => $position, 'isPublished' => true]);
        return $menu;
    }

    /**
     * Get a Menu defined by name
     *
     * @return Menu|null $menu
     */
    public function getMenuByName($name) {
        $menu = $this->menuRepo->findOneBy(['name' => $name]);
        return $menu;
    }

    /**
     * Get a Published Menu defined by name
     *
     * @return Menu|null $menu
     */
    public function getPublishedMenuByName($name) {
        $menu = $this->menuRepo->findOneBy(['position' => $name, 'isPublished' => true]);
        return $menu;
    }

    /**
     * Get a Menu defined by id
     *
     * @return Menu|null $menu
     */
    public function getMenuById($name) {
        $menu = $this->menuRepo->findOneBy(['id' => $id]);
        return $menu;
    }

    /**
     * Get a Published Menu defined by id
     *
     * @return Menu|null $menu
     */
    public function getPublishedMenuById($id) {
        $menu = $this->menuRepo->findOneBy(['position' => $id, 'isPublished' => true]);
        return $menu;
    }
}
