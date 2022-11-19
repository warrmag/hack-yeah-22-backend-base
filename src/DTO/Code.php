<?php

namespace App\DTO;

class Code
{
    public function __construct(
        public readonly string $code
    )
    {
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
