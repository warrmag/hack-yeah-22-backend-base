<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Trash
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'string', unique: true)]
        public readonly string $code,
        #[ORM\Column(type: 'string')]
        public readonly string $title = '',
        #[ORM\Column(type: 'string')]
        public readonly string $description = '',
        #[ORM\Column(type: 'string')]
        public readonly string $imageUrl = '',
    )
    {
    }
}
