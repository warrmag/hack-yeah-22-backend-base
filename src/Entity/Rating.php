<?php

namespace App\Entity;

use App\Enum\TrashType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Rating
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    public readonly Uuid $id;

    public function __construct(
        #[ORM\Column(type: 'string')]
        public readonly string $trash,
        #[ORM\Column(type: 'string', enumType: TrashType::class)]
        public readonly TrashType $trashType,
        #[ORM\Column(type: 'text', nullable: true)]
        public readonly ?string $comment
    )
    {
        $this->id = Uuid::v4();
    }
}
