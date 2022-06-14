<?php

declare(strict_types=1);

namespace App\Core\Interfaces;

use App\Core\Entity\AbstractSupervisedEntity;
    
interface SupervisorInterface
{
    /**
     * @param int|null $identifier
     * @return AbstractSupervisedEntity
     */
    public function initiate(int $identifier = null): AbstractSupervisedEntity;

    /**
     * @return AbstractSupervisedEntity
     */
    public function create(): AbstractSupervisedEntity;

    /**
     * @param AbstractSupervisedEntity $supervisedEntity
     */
    public function save(AbstractSupervisedEntity $supervisedEntity): void;

    /**
     * @param AbstractSupervisedEntity $supervisedEntity
     */
    public function delete(AbstractSupervisedEntity $supervisedEntity): void;
}
