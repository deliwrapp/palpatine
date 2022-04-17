<?php

namespace App\Security\Repository;

use App\Security\Entity\ControlAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ControlAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method ControlAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method ControlAccess[]    findAll()
 * @method ControlAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControlAccess::class);
    }
}