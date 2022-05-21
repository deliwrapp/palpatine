<?php

declare(strict_types=1);

namespace App\Core\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Exception;

trait SoftEditionTrackingTrait
{
    /**
     * @var DateTimeInterface
     * @Column(type="date", name="created_at")
     */
    private $createdAt;

    /**
     * @var DateTimeInterface
     * @Column(type="date", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * @PrePersist
     * @throws Exception
     */
    public function updateCreatedAt(): void
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     *
     * @PreUpdate
     * @throws Exception
     */
    public function updateUpdatedAt()
    {
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
