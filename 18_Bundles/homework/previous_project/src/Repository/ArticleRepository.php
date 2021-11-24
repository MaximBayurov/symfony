<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

     /**
      * @return Article[] Returns an array of Article objects
      */
    public function findLatestPublished()
    {
        return $this->published($this->latest())
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithSearchQuery(?string $search)
    {
        $qb = $this->getOrCreateQueryBuilder();

        $qb
            ->innerJoin('a.author', 'u')
            ->addSelect('u')
        ;

        if ($search) {
            $qb
                ->andWhere('a.title LIKE :search OR a.description LIKE :search OR a.body LIKE :search OR u.firstName LIKE :search')
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        return $qb
            ->orderBy('a.createdAt', 'DESC')
        ;
    }

    public function findAllPublishedLastWeek()
    {
        return $this->published($this->latest())
            ->andWhere('a.publishedAt >= :week_ago')
            ->setParameter('week_ago', new \DateTime('-1 week'))
            ->getQuery()
            ->getResult()
        ;
    }
    
     /**
      * @return Article[] Returns an array of Article objects
      */
    public function findLatest()
    {
        return $this->latest()
            ->getQuery()
            ->getResult()
        ;
    }
    
     /**
      * @return Article[] Returns an array of Article objects
      */
    public function findPublished()
    {
        return $this->published()            
            ->getQuery()
            ->getResult()
        ;
    }
    
     /**
      * @return Article[] Returns an array of Article objects
      */
    public function countByPeriod(DateTime $dateFrom, DateTime $dateTo)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $qb
            ->select($qb->expr()->count('a.id'))
            ->andWhere('a.createdAt >= :dateFrom AND a.createdAt <= :dateTo')
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
     /**
      * @return Article[] Returns an array of Article objects
      */
    public function countPublishedByPeriod(DateTime $dateFrom, DateTime $dateTo)
    {
        $qb = $this->published();
        return $qb
            ->select($qb->expr()->count('a.id'))
            ->andWhere('a.publishedAt >= :dateFrom AND a.publishedAt <= :dateTo')
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    private function published(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }
    
    private function latest(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->orderBy('a.publishedAt', 'DESC');
    }

    /**
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?? $this->createQueryBuilder('a');
    }
}
