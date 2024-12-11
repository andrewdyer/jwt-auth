<?php

namespace Anddye\JWTAuth\Providers;

interface JWTProviderInterface
{
    public function decode(string $token): mixed;

    public function encode(array $claims): string;
}
