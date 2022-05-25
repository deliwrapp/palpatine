<?php
// src/Core/Model/PageDuplication.php
namespace App\Core\Model;

class PageDuplication
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $locale;

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
     * @var string $name
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
     * @var string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}