<?php

declare(strict_types=1);

namespace App\Core\Traits;

use Doctrine\ORM\Mapping\OneToOne;
use App\Core\Entity\File;

trait FileEntityFieldTrait
{
    /**
     * @OneToOne(targetEntity="App\Core\Entity\File")
     */
    private $file;

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;
        return $this;
    }
}
