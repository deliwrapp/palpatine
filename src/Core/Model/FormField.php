<?php
// src/Core/Model/FormField.php
namespace App\Core\Model;

class FormField
{
    /** @var string $label */
    private $label;

    /** @var string $type */
    private $type;

    /** @var string $defaultValue */
    private $defaultValue;

    /** @var string $placeholder */
    private $placeholder;

    /** @var array $options */
    private $options;

    /**
     * Get label
     *
     * @return string $label
     */
    public function getLabel(): string
    {
        return $this->label;
    }
    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * Set type
     *
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get Default Value
     *
     * @return string $defaultValue
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }
    /**
     * Set Default Value
     *
     * @param string $defaultValue
     */
    public function setDefaultValue(?string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Get placeholder
     *
     * @return string $placeholder
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
    /**
     * Set placeholder
     *
     * @param string $placeholder
     */
    public function setPlaceholder(?string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }


}