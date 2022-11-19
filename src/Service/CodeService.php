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

    public function obtainTrash(Code $code): Trash
    {
        $trash = $this->em->find(Trash::class, (string)$code);
        if ($trash instanceof Trash) {
            return $trash;
        }

        $trash = new Trash(
            (string) $code
        );
        $this->em->persist($trash);

        $this->em->flush();
    }
}
