<?php

namespace Anddye\JwtAuth\Tests\Stubs\Providers;

use Anddye\JwtAuth\Providers\AuthProviderInterface;
use Anddye\JwtAuth\Tests\Stubs\Models\User;

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

    /**
     * @param string $username
     * @param string $password
     *
     * @return mixed
     */
    public function byCredentials(string $username, string $password)
    {
        if ($this->user->getUsername() !== $username) {
            return null;
        }

        if (!password_verify($password, $this->user->getPassword())) {
            return null;
        }

        return $this->user;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function byId(int $id)
    {
        if ($this->user->getId() !== $id) {
            return null;
        }

        return $this->user;
    }
}
