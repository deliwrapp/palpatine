<?php

namespace App\Core\Repository;

use App\Core\Entity\FormModelField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method FormModelField|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormModelField|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormModelField[]    findAll()
 * @method FormModelField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormModelFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormModelField::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FormModelField $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(FormModelField $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}
