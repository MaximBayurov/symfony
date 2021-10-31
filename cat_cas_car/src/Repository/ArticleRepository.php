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
    
    private function latest(?QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->orderBy('a.publishedAt', 'DESC');
    }
    
    private function published(?QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }
    
    private function getOrCreateQueryBuilder(?QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?? $this->createQueryBuilder('a')->innerJoin('a.comments', 'c')->addSelect('c');
    }
    
    public function findBySlug($slug)
    {
        return $this->getOrCreateQueryBuilder()->andWhere('a.slug = :slug')->setParameter('slug', "$slug")->getQuery()->getSingleResult();
    }
}
