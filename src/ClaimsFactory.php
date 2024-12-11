<?php

namespace Anddye\JWTAuth;

final class ClaimsFactory
{
    protected int $exp;

    protected int $iat;

    protected string $iss;

    protected string $jti;

    protected int $nbf;

    public static function build(array $claims): ClaimsFactory
    {
        $claimsFactory = new ClaimsFactory();

        if (isset($claims['exp'])) {
            $claimsFactory->setExp($claims['exp']);
        }

        if (isset($claims['iat'])) {
            $claimsFactory->setIat($claims['iat']);
        }

        if (isset($claims['iss'])) {
            $claimsFactory->setIss($claims['iss']);
        }

        if (isset($claims['jti'])) {
            $claimsFactory->setJti($claims['jti']);
        }

        if (isset($claims['nbf'])) {
            $claimsFactory->setNbf($claims['nbf']);
        }

        return $claimsFactory;
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
}
