<?php

namespace App\Core\Repository;

use App\Core\Entity\Folder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Folder $entity, bool $flush = true): void
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
    public function remove(Folder $entity, bool $flush = true): void
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
     * @param bool $folderId = null
     * @return Folder[] Returns an array of Page objects
     */
    public function findFoldersByRoleAndFolder($roles, int $folderId = null)
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
            ->setParameter('roles', $roles)
            ->getQuery()
            ->getResult();
        
        return $qb;
    }
}
