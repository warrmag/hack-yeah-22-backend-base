<?php

namespace App\Entity;

use App\Enum\TrashType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Rating
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        public readonly string $trash,
        #[ORM\Column(type: 'string', enumType: TrashType::class)]
        public readonly TrashType $trashType,
        #[ORM\Column(type: 'text', nullable: true)]
        public readonly ?string $comment
    )
    {
    }
}
