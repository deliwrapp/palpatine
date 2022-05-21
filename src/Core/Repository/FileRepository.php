<?php

namespace App\Core\Repository;

use App\Core\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(File $entity, bool $flush = true): void
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
    public function remove(File $entity, bool $flush = true): void
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

    /**
     * findFilesByRoleAndFolder()
     * @param string|array $roles
     * @param int $folderId = null
     * @return File[] Returns an array of Page objects
     */
    public function findFilesByRoleAndFolder($roles, int $folderId = null)
    {
        if (is_array($roles)) 
        {
            $roles[] = null;
        }
        if (is_string($roles)) {
            $roles = [$roles, null];
        }
        if (!is_array($roles) && !is_string($roles)) {
            $roles = [null];
        }
        $qb = $this->createQueryBuilder('f');
        $qb->andWhere('f.roleAccess IN(:roles)')
            ->andWhere('f.folder = :folderId')
            ->setParameter('roles', $roles)
            ->setParameter('folderId', $folderId)
            ->getQuery()
            ->getResult();
        
        return $qb;
    }
}
