<?php

namespace App\Service;

use App\DTO\RatingRequest;
use App\Entity\Rating;
use App\Entity\Trash;
use App\Enum\TrashType;
use Doctrine\ORM\EntityManagerInterface;

class OpinionService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function addOpinion(Trash $trash, RatingRequest $dto): void
    {
        $rating = new Rating($trash->code, TrashType::from($dto->type), $dto->description);

        $this->em->persist($rating);
        $this->em->flush();
    }
}
