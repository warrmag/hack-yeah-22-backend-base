<?php

namespace App\Service;

use App\DTO\Code;
use App\Entity\Trash;
use Doctrine\ORM\EntityManagerInterface;

class CodeService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly BarCodeLookupClient $barCodeLookupClient,
    )
    {
    }

    /** @throws \App\Exception\BarCodeLookupProductNotFoundException */
    public function obtainTrash(Code $code): Trash
    {
        $trash = $this->em->find(Trash::class, (string)$code);
        if ($trash instanceof Trash) {
            return $trash;
        }

        $barCodeLookupProduct = $this->barCodeLookupClient->findProductByBarCode((string) $code);
        $trash = new Trash(
            code: (string) $code,
            title: $barCodeLookupProduct->title,
            description: $barCodeLookupProduct->description,
            imageUrl: $barCodeLookupProduct->imageUrl,
        );

        $this->em->persist($trash);
        $this->em->flush();

        return $trash;
    }
}
