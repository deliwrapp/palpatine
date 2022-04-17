<?php

namespace App\Security\Entity;

use App\Security\Repository\ControlAccessRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use App\Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;


/**
 * @ORM\Entity(repositoryClass=ControlAccessRepository::class)
 * @HasLifecycleCallbacks
 */
class ControlAccess implements \Serializable
{

        public function __construct()
        {
                $this->isVerified = false;
                $this->isActive = true;
                $this->isRestricted = false;
        }

        use SoftEditionTrackingTrait;

        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @var string The controller reference
         * @ORM\Column(type="string")
         */
        private $controllerIdentifier;


        /**
         * @ORM\Column(type="boolean")
         */
        private $isVerified = false;

        /**
         * @ORM\Column(type="boolean")
        */
        private $isRestricted;

        /**
         * @ORM\Column(type="boolean")
        */
        private $isActive;

        /**
         * @ORM\ManyToOne(targetEntity="App\Security\Entity\User")
        */
        private $user;
        

        public function getId(): ?int
        {
                return $this->id;
        }

        public function getControllerIdentifier(): ?string
        {
            return $this->controllerIdentifier;
        }
    
        public function setControllerIdentifier(?string $controllerIdentifier): self
        {
            $this->controllerIdentifier = $controllerIdentifier;
    
            return $this;
        }

        public function getIsVerified(): bool
        {
            return $this->isVerified;
        }

        public function isVerified(): bool
        {
            return $this->isVerified;
        }

        public function setIsVerified(bool $isVerified): self
        {
            $this->isVerified = $isVerified;

            return $this;
        }

        function getIsActive() {
                return $this->isActive;
        }
        function setIsActive() {
                $this->isActive = false;
        }
        
        public function getIsRestricted(): ?bool
        {
                return $this->isRestricted;
        }
        public function setIsRestricted(): self
        {
                $this->isRestricted = true;

                return $this;
        }

        public function getAuthor():User
        {
            return $this->author;
        }
    
        public function setAuthor(User $author): self
        {
            $this->author = $author;
    
            return $this;
        }

        public function __toString()
        {
          return (string) $this->getControllerIdentifier();
        }
    
        /** @see \Serializable::serialize() */
        public function serialize() {
                return serialize(array(
                $this->id,
                $this->controllerIdentifier,
                $this->isVerified,
                $this->isActive,
                $this->isRestricted,
                ));
        }

        /** @see \Serializable::unserialize() */
        public function unserialize($serialized) {
                list (
                $this->id,
                $this->controllerIdentifier,
                $this->isVerified,
                $this->isActive,
                $this->isRestricted,
                ) = unserialize($serialized);
        }

}