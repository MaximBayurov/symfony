<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }
    
    public function findAllWithSearchQuery(
        float|\Symfony\Component\HttpFoundation\InputBag|bool|int|string|null $search,
        bool $showDeleted
    ) {
        $queryBuilder = $this->createQueryBuilder('t')
            ->leftJoin('t.articles', 'a')
            ->addSelect('a')
        ;
    
        if ($search) {
            $queryBuilder
                ->andWhere('t.name LIKE :search OR t.slug LIKE :search OR a.title LIKE :search')
                ->setParameter('search', "%$search%");
        }
    
        if ($showDeleted === false) {
            $queryBuilder->andWhere('t.deletedAt IS NULL');
        }
    
        return $queryBuilder
            ->orderBy('t.createdAt', 'DESC');
    }
}
