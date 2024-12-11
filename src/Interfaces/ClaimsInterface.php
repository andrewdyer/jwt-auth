<?php

namespace Anddye\JWTAuth\Interfaces;

interface ClaimsInterface
{
    public function setExp(int $exp);

    public function setIat(int $iat);

    public function setIss(string $iss);

    public function setJti(string $jti);

    public function setNbf(int $nbf);

    public function getExp(): int;

    public function getIat(): int;

    public function getIss(): string;

    public function getJti(): string;

    public function getNbf(): int;
}
