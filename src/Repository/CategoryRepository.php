<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findFullBySlug(string $slug): ?Category
    {
        return $this->createQueryBuilder('c') // SELECT c.* FROM category c
        ->select('c', 'p') // SELECT c.*, p.*
        ->join('c.publications', 'p') // JOIN publication_category + JOIN publication p
        ->where('c.slug = :slug') // WHERE c.slug = ?
        ->setParameter('slug', $slug) // ADD PARAMETER 0, $slug
        ->orderBy('p.releasedAt', 'DESC') // ORDER BY p.releasedAt DESC
        ->getQuery() // MET EN FORME LA REQUETE POUR L'EXECUTER
        ->getOneOrNullResult(); // EXECUTE LA REQUETE (Le oneOrNullResult fait secrètement un LIMIT 1)
    }
}
