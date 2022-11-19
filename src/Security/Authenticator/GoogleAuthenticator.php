<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_auth_google_check';
    }

    private function getUser()
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($this->getGoogleClient()->getAccessToken());

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(
                [
                    'provider' => User::PROVIDER_GOOGLE,
                    'providerId' => $googleUser->getId(),
                    'email' => $googleUser->getEmail(),
                ]
            );

        if ($existingUser) {
            return $existingUser;
        }

        $user = new User(
            $googleUser->getEmail(),
            User::PROVIDER_GOOGLE,
            $googleUser->getId(),
            $googleUser->getFirstName(),
            $googleUser->getLastName()
        );

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function getGoogleClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('google_default');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        $request->request->set('token', $this->getGoogleClient()->getAccessToken()->getToken());

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->getUser();

        return new SelfValidatingPassport(new UserBadge($user->email, fn () => $user));
    }
}
