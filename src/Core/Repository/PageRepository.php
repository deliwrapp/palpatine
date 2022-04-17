<?php

namespace App\Core\Repository;

use App\Core\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
