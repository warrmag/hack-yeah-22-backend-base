<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'index', methods: [Request::METHOD_GET])]
final class Index
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['ok']);
    }
}
