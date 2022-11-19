<?php

namespace App\Service;

use App\DTO\Code;
use App\Entity\Trash;
use Doctrine\ORM\EntityManagerInterface;

class CodeService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Code $code): void
    {
        $trash = new Trash(
            (string) $code
        );
        $this->em->persist($trash);

        $this->em->flush();
    }
}
