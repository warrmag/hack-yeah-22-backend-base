<?php

namespace App\Enum;

use App\Entity\Trash;

enum TrashType: string
{
    case Paper = 'Paper';
    case Bio = 'Bio';
    case MetalAndPlastics = 'MetalAndPlastics';
    case Bulky = 'Bulky';
    case Glass = 'Glass';
    case Green = 'Green';
    case Mixed = 'Mixed';

    public function fromString(string $type): self
    {
        return match ($type) {
            'Paper' => TrashType::Paper,
            'Bio' => TrashType::Bio,
            'MetalAndPlastics' => TrashType::MetalAndPlastics,
            'Bulky' => TrashType::Bulky,
            'Glass' => TrashType::Glass,
            'Green' => TrashType::Green,
            'Mixed' => TrashType::Mixed
        };
    }
}
