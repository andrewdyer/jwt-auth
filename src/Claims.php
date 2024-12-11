<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Interfaces\ClaimsInterface;

class Claims implements ClaimsInterface
{
    private int $exp;
    private int $iat;
    private string $iss;
    private string $jti;
    private int $nbf;

    public function setExp(int $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    public function setIat(int $iat): self
    {
        $this->iat = $iat;

        return $this;
    }

    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    public function setJti(string $jti): self
    {
        $this->jti = $jti;

        return $this;
    }

    public function setNbf(int $nbf): self
    {
        $this->nbf = $nbf;

        return $this;
    }

    public function getExp(): int
    {
        return $this->exp;
    }

    public function getIat(): int
    {
        return $this->iat;
    }

    public function getIss(): string
    {
        return $this->iss;
    }

    public function getJti(): string
    {
        return $this->jti;
    }

    public function getNbf(): int
    {
        return $this->nbf;
    }
}
