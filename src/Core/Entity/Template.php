<?php

namespace App\Core\Entity;

use App\Core\Repository\TemplateRepository;
use App\Core\Entity\SoftEditionTrackingTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use ArrayAccess;

/**
 * @ORM\Entity(repositoryClass=TemplateRepository::class) 
 * @HasLifecycleCallbacks
 */
class Template implements ArrayAccess
{
    public function __construct()
    {
        $this->isPublished = false;
    }

    use SoftEditionTrackingTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $templatePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cssLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scriptLink;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    public function setTemplatePath(string $templatePath): self
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getCssLink(): ?string
    {
        return $this->cssLink;
    }

    public function setCssLink(?string $cssLink): self
    {
        $this->cssLink = $cssLink;

        return $this;
    }

    public function getScriptLink(): ?string
    {
        return $this->scriptLink;
    }

    public function setScriptLink(?string $scriptLink): self
    {
        $this->scriptLink = $scriptLink;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }
    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getTemplatePath();
    }

    public function duplicate(Template $template): Template
    {
        $template->setName($this->name);
        $template->setTemplatePath($this->templatePath);
        $template->setCssLink($this->cssLink);
        $template->setScriptLink($this->scriptLink);
        return $template;
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
