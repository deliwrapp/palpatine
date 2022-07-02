<?php
// src/Core/Model/ModulesInfo.php
namespace App\Core\Model;

use Doctrine\Common\Collections\ArrayCollection;

class ModulesInfo
{

    /** @var array */
    private $coreModules;

    /** @var array */
    private $extensionModules;

    /** @var array */
    private $themeModules;

    /** @var array */
    private $blockModules;

    /**
     * Construct ModulesInfo
     *
     * @return ModulesInfo
     */
    public function __construct()
    {
        $this->coreModules = new ArrayCollection();
        $this->extensionModules = new ArrayCollection();
        $this->themeModules = new ArrayCollection();
        $this->blockModules = new ArrayCollection();
    }

    /**
     * Get Core Modules
     *
     * @return array $coreModules
     * @return ArrayCollection|coreModules[]
     */
    public function getCoreModules(): ?ArrayCollection
    {
        return $this->coreModules;
    }
    /**
     * Set Core Modules
     *
     * @param ArrayCollection $coreModules
     */
    public function setCoreModules(?ArrayCollection $coreModules): void
    {
        $this->coreModules = $coreModules;
    }
    /**
     * @param $module
     */
    public function addCoreModule($module): void
    {
        if (!$this->coreModules->contains($module)) {
            $this->coreModules[] = $module;
        }
    }
    /**
     * @param $module
     */
    public function removeCoreModule($module)
    {
        if (!$this->coreModules->contains($module)) {
            return;
        }
        $this->coreModules->removeElement($module);
    }

    /**
     * Get Extension Modules
     *
     * @return ArrayCollection $extensionModules
     */
    public function getExtensionModules(): ?ArrayCollection
    {
        return $this->extensionModules;
    }
    /**
     * Set Extension Modules
     *
     * @param ArrayCollection $extensionModules
     */
    public function setExtensionModules(?ArrayCollection $extensionModules): void
    {
        $this->extensionModules = $extensionModules;
    }
    /**
     * @param $module
     */
    public function addExtensionModule($module): void
    {
        if (!$this->extensionModules->contains($module)) {
            $this->extensionModules[] = $module;
        }
    }
    /**
     * @param $module
     */
    public function removeExtensionModule($module)
    {
        if (!$this->extensionModules->contains($module)) {
            return;
        }
        $this->extensionModules->removeElement($module);
    }

    /**
     * Get Theme Modules
     *
     * @return array $themeModules
     */
    public function getThemeModules(): ?ArrayCollection
    {
        return $this->themeModules;
    }
    /**
     * Set Theme Modules
     *
     * @param array $themeModules
     */
    public function setThemeModules(?ArrayCollection $themeModules): void
    {
        $this->themeModules = $themeModules;
    }
    /**
     * @param $module
     */
    public function addThemeModule($module): void
    {
        if (!$this->themeModules->contains($module)) {
            $this->themeModules[] = $module;
        }
    }
    /**
     * @param $module
     */
    public function removeThemeModule($module)
    {
        if (!$this->themeModules->contains($module)) {
            return;
        }
        $this->themeModules->removeElement($module);
    }

    /**
     * Get Block Modules
     *
     * @return array $blockModules
     */
    public function getBlockModules(): ?ArrayCollection
    {
        return $this->blockModules;
    }
    /**
     * Set Block Modules
     *
     * @param array $blockModules
     */
    public function setBlockModules(?ArrayCollection $blockModules): void
    {
        $this->blockModules = $blockModules;
    }
    /**
     * @param $module
     */
    public function addBlockModule($module): void
    {
        if (!$this->blockModules->contains($module)) {
            $this->blockModules[] = $module;
        }
    }
    /**
     * @param $module
     */
    public function removeBlockModule($module)
    {
        if (!$this->blockModules->contains($module)) {
            return;
        }
        $this->blockModules->removeElement($module);
    }
}