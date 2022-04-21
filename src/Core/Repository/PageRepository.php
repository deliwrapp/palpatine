<?php

namespace App\Core\Repository;

use App\Core\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Page $entity, bool $flush = true): void
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
    public function remove(Page $entity, bool $flush = true): void
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
     * @return Page[] Returns an array of Page objects
     */
    public function findByPageGroup($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.pageGroupId = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Page[] Returns an array of Page objects
     */
    public function getPages()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return Page[] Returns an array of Page objects
     */
    public function findByLocale($locale)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Page[] Returns an array of Page objects
     */
    public function findOneByPageGroupAndLocale($group, $locale)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.pageGroupId = :group')
            ->andWhere('p.locale = :locale')
            ->setParameter('group', $group)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Page[] Returns an array of Page objects
     */
    public function findOneByIsHomepageAndLocale($isHomepage, $locale)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isHomepage = :isHomepage')
            ->andWhere('p.locale = :locale')
            ->setParameter('isHomepage', $isHomepage)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
