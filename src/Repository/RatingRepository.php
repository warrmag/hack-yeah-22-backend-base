<?php

namespace App\Repository;

use App\DTO\RatingRequest;
use App\Entity\Trash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingRequest::class);
    }

    public function fetchRating(Trash $trash): array
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT count(r.id), r.trash_type from rating as r
                where r.trash = :trash
                group by r.trash_type
            ')
            ->setParameter('trash', $trash->code);
        $res = $query->getResult();
        dump($res);

        return [];
    }
}
