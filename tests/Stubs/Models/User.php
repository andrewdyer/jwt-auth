<?php

namespace Anddye\JwtAuth\Tests\Stubs\Models;

use Anddye\JwtAuth\Contracts\JwtSubject;

class User implements JwtSubject
{
    protected int $id;

    protected string $password;

    protected string $username;

    public function getId(): int
    {
        return $this->id;
    }

    public function getJwtIdentifier(): int
    {
        return $this->getId();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
