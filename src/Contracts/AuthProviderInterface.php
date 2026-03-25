<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

interface AuthProviderInterface
{
    public function byCredentials(string $username, string $password): mixed;

    public function byId(int|string $id): mixed;
}
