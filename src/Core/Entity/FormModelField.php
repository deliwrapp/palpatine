<?php
// src/Core/Model/FormField.php
namespace App\Core\Entity;

use App\Core\Traits\SoftEditionTrackingTrait;
use App\Core\Repository\FormModelFieldRepository;
use App\Core\Entity\FormModel;
use Doctrine\ORM\Mapping as ORM;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=FormModelFieldRepository::class)
 */
class FormModelField implements \Serializable, ArrayAccess
{
    public function __construct()
    {
            $this->label = 'new-field';
            $this->type = "text";
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /** 
     * @var string $label The name label reference
     * @ORM\Column(type="string")
    */
    private $label;

    /** 
     * @var string $type The field type
     * @ORM\Column(type="string")
    */
    private $type;

    /** 
     * @var string $defaultValue The field type default value
     * @ORM\Column(type="string", nullable=true)
    */
    private $defaultValue;

    /**
     * @var string $placeholder The field type default placeholder
     * @ORM\Column(type="string", nullable=true)
    */
    private $placeholder;

    /**
     * @var string $options The field options default placeholder
     * @ORM\Column(type="string", nullable=true)
    */
    private $options;

    /**
     * The Form Model Container
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\FormModel", inversedBy="field")
    */
    private $formModel;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    /**
     * Get Options
     *
     * @return string $options
     */
    public function getOptions(): ?string
    {
        return $this->options;
    }
    /**
     * Set Options
     *
     * @param string $options
     */
    public function setOptions(string $options): void
    {
        $this->options = $options;
    }
   
    /**
     * Get FormModel
     *
     * @return FormModel $formModel
     */
    public function getFormModel(): FormModel
    {
        return $this->formModel;
    }
    /**
     * Set FormModel
     *
     * @param FormModel $formModel
     */
    public function setFormModel(FormModel $formModel): void
    {
        $this->formModel = $formModel;
    }

    public function __toString()
    {
        return (string) $this->getLabel();
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
            return serialize(array(
                $this->id,
                $this->label,
                $this->type,
                $this->defaultValue,
                $this->placeholder,
            ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
            list (
                $this->id,
                $this->label,
                $this->type,
                $this->defaultValue,
                $this->placeholder
            ) = unserialize($serialized);
    }

    public function duplicate(FormModelField $formModelField): FormModelField
    {
        $formModelField->setLabel($this->label);
        $formModelField->setType($this->type);
        $formModelField->setDefaultValue($this->defaultValue);
        $formModelField->setPlaceholder($this->placeholder);
        $formModelField->setFormModel($this->formModel);
        return $formModelField;
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