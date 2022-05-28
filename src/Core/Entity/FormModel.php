<?php

namespace App\Core\Entity;

use App\Core\Traits\SoftEditionTrackingTrait;
use App\Core\Repository\FormModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
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
         * @ORM\Column(type="boolean")
         */
        private $isPublished;

        /**
         * @ORM\Column(type="string", length=8)
         */
        private $locale;

        /**
         * @var array The Form model field
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

        public function getIsPublished(): bool
        {
            return $this->published;
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

        /**
         * @return array|null
         */
        public function getFields()
        {
            return $this->fields;
        }
        
        /**
         * @param array $field
         */
        public function addField(array $field): void
        {
            if (!$this->fields->contains($field)) {
                $this->fields[] = $field;
            }
        }

        /**
         * @param array $field
         */
        public function removeField(array $field)
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
        public function serialize() {
                return serialize(array(
                $this->id,
                $this->name,
                $this->isPublished,
                $this->locale,
                ));
        }

        /** @see \Serializable::unserialize() */
        public function unserialize($serialized) {
                list (
                $this->id,
                $this->name,
                $this->isPublished,
                $this->locale,
                ) = unserialize($serialized);
        }

        public function duplicate(FormModel $formModel): Page
        {
            $formModel->setName($this->name);
            $formModel->setLocale($this->locale);
            return $formModel;
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