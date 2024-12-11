<?php

namespace Anddye\JwtAuth\Providers;

interface JwtProviderInterface
{
    public function decode(string $token): mixed;

    public function encode(array $claims): string;
}
