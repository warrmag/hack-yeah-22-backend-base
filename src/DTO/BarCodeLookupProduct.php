<?php

namespace App\DTO;

class BarCodeLookupProduct
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $barCode,
        public readonly string $imageUrl,
    ) {}
}
