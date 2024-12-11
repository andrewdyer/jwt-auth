<?php

namespace Anddye\JWTAuth\Providers;

interface AuthProviderInterface
{
    public function byCredentials(string $username, string $password): mixed;

    public function byId(int $id): mixed;
}
