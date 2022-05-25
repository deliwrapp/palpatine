<?php
// src/Core/Model/AppConfig.php
namespace App\Core\Model;

class AppDataModel
{
    /** @var array */
    private $users;

    /** @var array */
    private $templates;

    /** @var array */
    private $files;

    /** @var array */
    private $forms;

    /** @var array */
    private $blocks;

    /** @var array */
    private $pages;

    /** @var array */
    private $menus;
    

    /**
     * Get Users
     *
     * @return array $users
     */
    public function getUsers(): ?array
    {
        return $this->users;
    }
    
    /**
     * Set Users
     *
     * @param array $users
     */
    public function setUsers(?array $users): void
    {
        $this->users = $users;
    }

    /**
     * Get Templates
     *
     * @return array $templates
     */
    public function getTemplates(): ?array
    {
        return $this->templates;
    }
    
    /**
     * Set Templates
     *
     * @param array $templates
     */
    public function setTemplates(?array $templates): void
    {
        $this->templates = $templates;
    }

    /**
     * Get Files
     *
     * @return array $files
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }
    
    /**
     * Set Files
     *
     * @param array $files
     */
    public function setFiles(?array $files): void
    {
        $this->files = $files;
    }

    /**
     * Get Forms
     *
     * @return array $forms
     */
    public function getForms(): ?array
    {
        return $this->forms;
    }
    
    /**
     * Set Forms
     *
     * @param array $forms
     */
    public function setForms(?array $forms): void
    {
        $this->forms = $forms;
    }

    /**
     * Get Blocks
     *
     * @return array $blocks
     */
    public function getBlocks(): ?array
    {
        return $this->blocks;
    }
    
    /**
     * Set Blocks
     *
     * @param array $blocks
     */
    public function setBlocks(?array $blocks): void
    {
        $this->blocks = $blocks;
    }

    /**
     * Get Pages
     *
     * @return array $pages
     */
    public function getPages(): ?array
    {
        return $this->pages;
    }
    
    /**
     * Set Pages
     *
     * @param array $pages
     */
    public function setPages(?array $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * Get Menus
     *
     * @return array $menus
     */
    public function getMenus(): ?array
    {
        return $this->menus;
    }
    
    /**
     * Set Menus
     *
     * @param array $menus
     */
    public function setMenus(?array $menus): void
    {
        $this->menus = $menus;
    }
}