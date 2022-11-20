<?php

declare(strict_types=1);

namespace App\Controller\Auth\Google;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/google/check', name: 'connect_auth_google_check')]
final class Check
{
    public function __construct(private readonly ClientRegistry $clientRegistry)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        dump($request->query->all());
        $client = $this->clientRegistry->getClient('google_default');

        dump($user = $client->fetchUser());
        //https://localhost/connect/google/check?state=4c408582ebfaa04b4321506a823d97b7&code=4%2F0AfgeXvvlm1wXKR9hDeelGjnfpd0gIhYNb5XP8iWdiDBMvjcr0oPV8Gn887CeHh6qz5NF1g&scope=email+profile+openid+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&authuser=0&prompt=consent
        return new JsonResponse($request->query->all());
    }
}
