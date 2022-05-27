<?php

namespace App\Core\Entity;

use App\Core\Traits\SoftEditionTrackingTrait;
use App\Core\Repository\FormModelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;


/**
 * @ORM\Entity(repositoryClass=FormModelRepository::class)
 * @HasLifecycleCallbacks
 */
class FormModel implements \Serializable
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
        private $published = false;
        

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

        public function getPublished(): bool
        {
            return $this->published;
        }

        public function isPublished(): bool
        {
            return $this->published;
        }

        public function setPublished(bool $published): self
        {
            $this->published = $published;

            return $this;
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
                $this->published,
                ));
        }

        /** @see \Serializable::unserialize() */
        public function unserialize($serialized) {
                list (
                $this->id,
                $this->name,
                $this->published,
                ) = unserialize($serialized);
        }

}