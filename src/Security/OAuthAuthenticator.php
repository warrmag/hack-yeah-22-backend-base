<?php

declare(strict_types=1);

namespace App\Security;

use League\OAuth2\Client\Provider\Google as GoogleProvider;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class OAuthAuthenticator extends AbstractAuthenticator
{
    private const ROUTE_LOGIN = 'auth.login';

    private const ROUTE_OAUTH_LOGIN = 'auth.connect.google';

    private const ROUTE_DASHBOARD = 'index';

    /**
     * @var GoogleProvider
     */
    private $provider;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(GoogleProvider $provider, UrlGeneratorInterface $urlGenerator)
    {
        $this->provider = $provider;
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(self::ROUTE_LOGIN)
        );
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === self::ROUTE_OAUTH_LOGIN;
    }

    public function getCredentials(Request $request): AccessTokenInterface
    {
        if (!$request->query->has('code')) {
            throw new CustomUserMessageAuthenticationException('Missing code.');
        }

        return $this->provider->getAccessToken('authorization_code', [
            'code' => $request->query->get('code')
        ]);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $googleUser = $this->provider->getResourceOwner($credentials);

        \assert($googleUser instanceof GoogleUser);

        try {
            $email = $googleUser->getEmail();

            \assert(\is_string($email));

            return $userProvider->loadUserByUsername($email);
        } catch (UserDoesNotExist $exception) {
            throw new CustomUserMessageAuthenticationException($exception->getMessage());
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        dump($exception);

        return new RedirectResponse(
            $this->urlGenerator->generate($request->headers->get('HTTP_ORIGIN'))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): JsonResponse
    {
        dump($token);
        dump('32131231312');

        return new JsonResponse(['token' => $token->getUserIdentifier()]);
    }

    public function supportsRememberMe(): bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $user = $this->getUser();

        return new SelfValidatingPassport(new UserBadge($user->email, fn () => $user));
    }
}
