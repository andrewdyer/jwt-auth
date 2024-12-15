<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Interfaces\ClaimsInterface;
use Anddye\JWTAuth\Interfaces\JWTSubject;

class Claims implements ClaimsInterface
{
    private string $aud;
    private int $exp;
    private int $iat;
    private string $iss;
    private string $jti;
    private int $nbf;
    private JWTSubject $sub;

    public function getAud(): string
    {
        return $this->aud;
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

    public function getSub(): JWTSubject
    {
        return $this->sub;
    }

    public function setAud(string $aud): self
    {
        $this->aud = $aud;

        return $this;
    }

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

    public function setSub(JWTSubject $sub): self
    {
        $this->sub = $sub;

        return $this;
    }
}
