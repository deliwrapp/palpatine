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
     * Get the Main Menu and locale
     *
     * @var string $locale
     * @return Menu|null $menu
     */
    public function getMainMenuLocalized(string $locale) {
        $menu = $this->menuRepo->findOneBy(['isMainMenu' => true, 'locale' => $locale]);
        return $menu;
    }

    /**
     * Get the Published Main Menu and locale
     *
     * @var string $locale
     * @return Menu|null $menu
     */
    public function getPublishedMainMenuLocalized(string $locale) {
        $menu = $this->menuRepo->findOneBy([
            'isMainMenu' => true,
            'isPublished' => true,
            'locale' => $locale
        ]);
        return $menu;
    }

    /**
     * Get a Menu defined by position and locale
     *
     * @var string $position
     * @var string $locale
     * @return Menu|null $menu
     */
    public function getMenuWithPositionAndLocale(string $position, string $locale) {
        $menu = $this->menuRepo->findOneBy([
            'position' => $position,
            'locale' => $locale
        ]);
        return $menu;
    }

    /**
     * Get a Published Menu defined by position and locale
     *
     * @var string $position
     * @var string $locale
     * @return Menu|null $menu
     */
    public function getPublishedMenuWithPositionAndLocale($position, string $locale) {
        $menu = $this->menuRepo->findOneBy([
            'position' => $position,
            'locale' => $locale,
            'isPublished' => true
        ]);
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
     * @param int $id
     * @return Menu|null $menu
     */
    public function getMenuById($id) {
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
