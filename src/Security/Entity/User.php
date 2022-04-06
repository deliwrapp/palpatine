<?php

namespace App\Security\Entity;

use App\Security\Repository\UserRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ArrayAccess;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email") 
 * @HasLifecycleCallbacks
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable, ArrayAccess
{

        public function __construct()
        {
                $this->isVerified = false;
                $this->isActive = false;
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
         * @ORM\Column(type="string", length=180, unique=true)
         *
         * @var string
         * 
         * @Assert\NotBlank()
         * @Assert\Email()
        */
        private $email;

        /**
         * @ORM\Column(type="string", length=180, unique=true)
         */
        private $username;

        /**
         * @ORM\Column(type="json")
         */
        private $roles = [];

        /**
         * @var string The hashed password
         * @ORM\Column(type="string")
         */
        private $password;

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
        

        public function getId(): ?int
        {
                return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(string $email): self
        {
            $this->email = $email;

            return $this;
        }

        /**
         * The public representation of the user
         *
         * @see UserInterface
         */
        public function getUserIdentifier(): string
        {
            return (string) $this->email;
        }

        public function getUsername(): string
        {
                return (string) $this->username;
        }

        public function setUsername(string $username): self
        {
                $this->username = $username;

                return $this;
        }

        /**
         * @see UserInterface
         */
        public function getRoles(): array
        {
                $roles = $this->roles;
                // guarantee every user at least has ROLE_USER
                $roles[] = 'ROLE_USER';

                return array_unique($roles);
        }

        public function setRoles(array $roles): self
        {
                $this->roles = $roles;

                return $this;
        }

        /**
         * @see UserInterface
         */
        public function getPassword(): string
        {
                return (string) $this->password;
        }

        public function setPassword(string $password): self
        {
                $this->password = $password;

                return $this;
        }

        /**
         * Returning a salt is only needed, if you are not using a modern
         * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
         *
         * @see UserInterface
         * @return
         */
        public function getSalt(): string
        {
                return '';
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
                // If you store any temporary, sensitive data on the user, clear it here
                // $this->plainPassword = null;
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
        function setIsActive($isActive) {
                $this->isActive = $isActive;
        }
        
        public function getIsRestricted(): ?bool
        {
                return $this->isRestricted;
        }
        public function setIsRestricted(?bool $isRestricted): self
        {
                $this->isRestricted = $isRestricted;

                return $this;
        }

        public function __toString()
        {
          return (string) $this->getEmail();
        }
    
        /** @see \Serializable::serialize() */
        public function serialize() {
                return serialize(array(
                $this->id,
                $this->email,
                $this->username,
                $this->password,
                $this->isVerified,
                $this->isActive,
                $this->isRestricted,
                ));
        }

        /** @see \Serializable::unserialize() */
        public function unserialize($serialized) {
                list (
                $this->id,
                $this->email,
                $this->username,
                $this->password,
                $this->isVerified,
                $this->isActive,
                $this->isRestricted,
                ) = unserialize($serialized);
        }

                
        public function offsetExists($offset) {
                // In this example we say that exists means it is not null
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