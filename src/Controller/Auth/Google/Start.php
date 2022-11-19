<?php

declare(strict_types=1);

namespace App\Controller\Auth\Google;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/google', name: 'connect_auth_google_start', methods: [Request::METHOD_GET])]
final class Start
{
    public function __construct(private readonly ClientRegistry $clientRegistry) {}

    public function __invoke(): RedirectResponse {
        return $this->clientRegistry->getClient('google_default')->redirect(['openid', 'profile', 'email']);
    }
}
