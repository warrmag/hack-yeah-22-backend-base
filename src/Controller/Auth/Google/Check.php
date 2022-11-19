<?php

declare(strict_types=1);

namespace App\Controller\Auth\Google;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/google/check', name: 'connect_auth_google_check')]
final class Check
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
