<?php

namespace App\Core\Entity;

use App\Core\Traits\SoftEditionTrackingTrait;
use App\Core\Repository\FormModelRepository;
use App\Core\Entity\Template;
use App\Core\Entity\FormModelField;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=FormModelRepository::class)
 * @HasLifecycleCallbacks
 */
class FormModel implements \Serializable, ArrayAccess
{
    public function __construct()
    {
        $this->isPublished = false;
        $this->name = 'new-form';
        $this->fields = new ArrayCollection();
        $this->sendTo = "test@test.test";
    }

    use SoftEditionTrackingTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string The name reference
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string The email address to send form
     * @ORM\Column(type="string")
     */
    private $sendTo;
    
    /**
     * @var bool The Publish status
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @var string The locale language
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $locale;

    /**
     * The form template path reference
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Template")
     */
    private $formTemplate;

    /**
     * The mail template path reference
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Template")
     */
    private $mailTemplate;

    /**
     * The Form model field
     * @ORM\OneToMany(targetEntity="App\Core\Entity\FormModelField", mappedBy="formModel", cascade={"persist", "remove"})
     */
    private $fields;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSendTo(): ?string
    {
        return $this->sendTo;
    }
    public function setSendTo(string $sendTo): self
    {
        $this->sendTo = $sendTo;

        return $this;
    }

    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }
    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    public function getFormTemplate(): ?Template
    {
        return $this->formTemplate;
    }

    public function setFormTemplate(?Template $formTemplate): self
    {
        $this->formTemplate = $formTemplate;

        return $this;
    }

    public function getMailTemplate(): ?Template
    {
        return $this->mailTemplate;
    }

    public function setMailTemplate(?Template $mailTemplate): self
    {
        $this->mailTemplate = $mailTemplate;

        return $this;
    }

    /**
     * @return Collection|FormModelField[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @return ArrayCollection|FormModelField[]
     */
    public function getFieldsArray()
    {
        return $this->fields;
    }
    /**
     * @param FormModelField $field
     */
    public function addField(FormModelField $field): void
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
            $field->setFormModel($this);
        }
    }
    /**
     * @param FormModelField $field
     */
    public function removeField(FormModelField $field)
    {
        if (!$this->fields->contains($field)) {
            return;
        }
        $this->fields->removeElement($field);
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->isPublished,
            $this->locale,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->isPublished,
            $this->locale,
        ) = unserialize($serialized);
    }

    public function duplicate(FormModel $formModel): FormModel
    {
        $formModel->setName($this->name);
        $formModel->setLocale($this->locale);
        return $formModel;
    }

    public function offsetExists($offset)
    {
        $value = $this->{"get$offset"}();
        return $value !== null;
    }

    public function offsetSet($offset, $value)
    {
        $this->{"set$offset"}($value);
    }

    public function offsetGet($offset)
    {
        return $this->{"get$offset"}();
    }

    public function offsetUnset($offset)
    {
        $this->{"set$offset"}(null);
    }
}
