<?php
// src/Core/FormObject/PageDuplication.php
namespace App\Core\FormObject;

class PageDuplication
{
    protected $name;
    protected $locale;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}