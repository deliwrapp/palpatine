<?php

namespace App\Core\Repository;

use App\Core\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
/**
 * class PageRepository extends ServiceEntityRepository
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    /**
     * _contruct()
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * add()
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
     * remove()
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
     * flush()
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
    
    /**
     * findByPageGroup()
     * @param string $value
     * @return Page[] Returns an array of Page objects
     */
    public function findByPageGroup(string $value)
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
     * findByPageGroup()
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
     * findByLocale()
     * @param string $locale
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
     * findOneByPageGroupAndLocale()
     * @param string $group
     * @param string $locale
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
     * findOneByPageGroupAndLocale()
     * @param bool $isHomepage
     * @param string $locale
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
