<?php

namespace Anddye\JWTAuth\Tests\Stubs\Providers;

use Anddye\JWTAuth\Interfaces\AuthProviderInterface;
use Anddye\JWTAuth\Tests\Stubs\Models\User;

class AuthProvider implements AuthProviderInterface
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
        $this->user->setId(1);
        $this->user->setUsername('andrewdyer');
        $this->user->setPassword(password_hash('password', PASSWORD_DEFAULT));
    }

    public function byCredentials(string $username, string $password): mixed
    {
        if ($this->user->getUsername() !== $username) {
            return null;
        }

        if (!password_verify($password, $this->user->getPassword())) {
            return null;
        }

        return $this->user;
    }

    public function byId(int $id): mixed
    {
        if ($this->user->getId() !== $id) {
            return null;
        }

        return $this->user;
    }
}
