<?php

namespace Anddye\JWTAuth\Interfaces;

interface JWTProviderInterface
{
    public function decode(string $token): mixed;

    public function encode(array $claims): string;
}
