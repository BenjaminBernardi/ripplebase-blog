<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function getAvgRatings(string $id)
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.notation) AS avgRatings')
            ->join('r.publication', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUserRate(string $userId, string $publicationId)
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('r.publication', 'p')
            ->where('u.id = :userId')
            ->andWhere('p.id = :publicationId')
            ->setParameter('userId', $userId)
            ->setParameter('publicationId', $publicationId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
