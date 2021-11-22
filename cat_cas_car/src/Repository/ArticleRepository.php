<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

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
        return $this->latest($this->published())->getQuery()->getResult();
    }
    
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findLatest()
    {
        return $this->latest()
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findPublished()
    {
        return $this->published()
            ->getQuery()
            ->getResult();
    }
    
    public function latest(?QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->orderBy('a.publishedAt', 'DESC');
    }
    
    private function published(?QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }
    
    private function getOrCreateQueryBuilder(?QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ??
            $this
                ->createQueryBuilder('a')
                ->leftJoin('a.comments', 'c')
                ->addSelect('c')
                ->leftJoin('a.tags', 't')
                ->addSelect('t');
    }
    
    public function findBySlug($slug)
    {
        return $this->getOrCreateQueryBuilder()->andWhere('a.slug = :slug')->setParameter('slug', "$slug")->getQuery()->getSingleResult();
    }
    
    public function findAllWithSearchQuery($search) {
        
        $queryBuilder = $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'u')
            ->addSelect('u')
        ;
        
        if ($search) {
            $queryBuilder
                ->andWhere('a.title LIKE :search OR a.body LIKE :search OR u.firstName LIKE :search')
                ->setParameter('search', "%$search%");
        }
        
        return $queryBuilder
            ->orderBy('a.createdAt', 'DESC');
    }
    
    public function findAllPublishedLastWeek(): array
    {
        return $this->published($this->latest())
            ->andWhere('a.publishedAt >= :week_ago')
            ->setParameter('week_ago', new \DateTime('-1 week'))
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function getCreatedCount(\DateTime $from, \DateTime $to): int
    {
        $result =  $this->latest()
            ->andWhere('a.createdAt >= :from')
            ->andWhere('a.createdAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->select('COUNT(a.id) as count')
            ->getQuery()
            ->getOneOrNullResult()
        ;
        
        if (!$result) {
            return 0;
        }
        return (int)$result['count'];
    }
    
    public function getPublishedCount(\DateTime $from, \DateTime $to)
    {
        $result =  $this->published($this->latest())
            ->andWhere('a.publishedAt >= :from')
            ->andWhere('a.publishedAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->select('COUNT(a.id) as count')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    
        if (!$result) {
            return 0;
        }
        return (int)$result['count'];
    }
}
