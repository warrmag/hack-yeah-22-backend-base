<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @param string $email
     */
    public function loadUserByUsername($email): SecurityUser
    {
        return new SecurityUser($this->userRepository->getOneByEmail(new Email($email)));
    }

    public function refreshUser(UserInterface $user): SecurityUser
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return $class === SecurityUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->em->find(User::class, $identifier);
    }
}
