<?php

namespace App\Core\Entity;

use App\Core\Entity\SoftEditionTrackingTrait;
use App\Core\Repository\MenuItemRepository;
use App\Core\Entity\Menu;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=MenuItemRepository::class)
 * @HasLifecycleCallbacks
 */
class MenuItem implements ArrayAccess
{
    public function __construct()
    {
        $this->externalLink = false;
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
     * @ORM\Column(type="boolean")
     */
    private $externalLink;

    /**
     * @ORM\Column(type="string")
     */
    private $path;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $customId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $customClass;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Menu", inversedBy="menuItems")
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Menu")
     */
    private $subMenu;
    
    
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

    public function getExternalLink(): ?bool
    {
        return $this->externalLink;
    }
    public function setExternalLink(?bool $externalLink): self
    {
        $this->externalLink = $externalLink;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->name;
    }
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }
    public function setCustomId(string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getCustomClass(): ?string
    {
        return $this->customClass;
    }
    public function setCustomClass(string $customClass): self
    {
        $this->customClass = $customClass;

        return $this;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
    public function setMenu(Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getSubMenu(): Menu
    {
        return $this->subMenu;
    }
    public function setSubMenu(Menu $subMenu): self
    {
        $this->subMenu = $subMenu;

        return $this;
    }
    public function removeSubMenu(): self
    {
        $this->subMenu = null;

        return $this;
    }

    public function duplicate(MenuItem $menu): MenuItem
    {
        $menu->setName($this->name);
        $menu->setExternalLink($this->externalLink);
        $menu->setPath($this->setPath);
        $menu->setCustomClass($this->customClass);
        $menu->setMenu($this->menu);
        $menu->setSubMenu($this->subMenu);
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
