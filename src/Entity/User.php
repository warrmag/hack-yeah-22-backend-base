<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class User implements UserInterface
{
    public const PROVIDER_GOOGLE = 'google';

    public const PROVIDER_FACEBOOK = 'facebook';

    public function __construct(
        #[
            ORM\Column(type: 'uuid', unique: true),
            ORM\Id,
            ORM\GeneratedValue,
            ORM\CustomIdGenerator(class: UuidGenerator::class),
            Assert\Uuid
        ]
        public readonly Uuid $id,
        #[
            ORM\Column(type: 'string', unique: true),
            Assert\Unique(normalizer: 'strtolower')
        ]
        public readonly string $email,
        #[
            ORM\Column(type: 'string'),
            Assert\Choice(choices: [
                self::PROVIDER_GOOGLE,
                self::PROVIDER_FACEBOOK,
            ])
        ]
        public readonly string $provider,
        #[ORM\Column(type: 'string')]
        public readonly string $providerId,
        #[ORM\Column(type: 'string')]
        public readonly string $firstName,
        #[ORM\Column(type: 'string')]
        public readonly string $lastName,
        #[ORM\Column(type: 'string', nullable: true)]
        private ?string $nickname = null
    ) {
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }
}
