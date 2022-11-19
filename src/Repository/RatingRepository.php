<?php

namespace App\Repository;

use App\DTO\RatingRequest;
use App\Entity\Rating;
use App\Entity\Trash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function fetchRating(Trash $trash): array
    {
        $query = $this->getEntityManager()->getConnection()
            ->prepare('
                SELECT count(r.id), r.trash_type from rating as r
                where r.trash = :trash
                group by r.trash_type
            ');
        $query->bindValue('trash', $trash->code);

        return $query->executeQuery()->fetchAllAssociative();
    }
}
