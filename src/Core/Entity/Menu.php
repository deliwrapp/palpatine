<?php

namespace App\Core\Entity;

use App\Core\Traits\SoftEditionTrackingTrait;
use App\Core\Repository\MenuRepository;
use App\Core\Entity\MenuItem;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * @UniqueEntity(fields="name", message="Name is already taken.")
 * @HasLifecycleCallbacks
 */
class Menu implements ArrayAccess
{
    public function __construct()
    {
        $this->name = "menu-";
        $this->isPublished = false;
        $this->isMainMenu = false;
        $this->menuItems = new ArrayCollection();
    }

    use SoftEditionTrackingTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $locale;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\MenuItem", mappedBy="menu", cascade={"persist", "remove"})
     */
    private $menuItems;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isMainMenu;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
 
    public function getPosition(): ?string
    {
        return $this->position;
    }
    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }
    public function removePosition(): self
    {
        $this->position = null;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
   
    /**
     * @return Collection|MenuItem[]
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    /**
     * @return ArrayCollection|MenuItem[]
     */
    public function getMenuItemsArray()
    {
        return $this->menuItems;
    }
    
    /**
     * @param MenuItem $menuItem
     */
    public function addMenuItem(MenuItem $menuItem): void
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems[] = $menuItem;
            $menuItem->setMenu($this);
        }
    }

    /**
     * @param MenuItem $menuItem
     */
    public function removeMenuItem(MenuItem $menuItem)
    {
        if (!$this->menuItems->contains($menuItem)) {
            return;
        }
        $this->menuItems->removeElement($menuItem);
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }
    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getIsMainMenu(): ?bool
    {
        return $this->isMainMenu;
    }
    public function setIsMainMenu(?bool $isMainMenu): self
    {
        $this->isMainMenu = $isMainMenu;

        return $this;
    }

    public function duplicate(Menu $menu): Menu
    {
        $menu->setName($this->name);
        $menu->setLocale($this->locale);
        return $menu;
    }
  
    public function offsetExists($offset) {
        $value = $this->{"get$offset"}();
        return $value !== null;
    }

    public function offsetSet($offset, $value) {
        $this->{"set$offset"}($value);
    }

    public function offsetGet($offset) {
        return $this->{"get$offset"}();
    }

    public function offsetUnset($offset) {
        $this->{"set$offset"}(null);
    }

}
