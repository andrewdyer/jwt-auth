<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

final readonly class InMemoryAuthProvider implements AuthProviderInterface
{
    public function __construct(
        private ?JwtSubjectInterface $user = null
    ) {
    }

    public function byCredentials(string $username, string $password): ?JwtSubjectInterface
    {
        return $this->user;
    }

    public function byId(int|string $id): ?JwtSubjectInterface
    {
        return $this->user;
    }
}
