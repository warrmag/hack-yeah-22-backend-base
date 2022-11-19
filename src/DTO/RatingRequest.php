<?php

namespace App\DTO;

use App\Enum\TrashType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RatingRequest
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $description
    )
    {
    }
}
