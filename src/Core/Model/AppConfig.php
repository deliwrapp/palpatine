<?php
// src/Core/Model/AppConfig.php
namespace App\Core\Model;

class AppConfig
{
    /** @var string */
    private $name;

    /** @var string */
    private $locale;

    /** @var array */
    private $parameters;

    /** @var array */
    private $adminConfig;

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get locale
     *
     * @return string $locale
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    
    /**
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Get parameters
     *
     * @return array $parameters
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }
    
    /**
     * Set parameters
     *
     * @param array $parameters
     */
    public function setParameters(?array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Get Admin Config
     *
     * @return array $adminConfig
     */
    public function getAdminConfig(): ?array
    {
        return $this->adminConfig;
    }
    
    /**
     * Set Admin Config
     *
     * @param array $adminConfig
     */
    public function setAdminConfig(?array $adminConfig): void
    {
        $this->adminConfig = $adminConfig;
    }
}