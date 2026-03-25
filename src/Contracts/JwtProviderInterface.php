<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

interface JwtProviderInterface
{
    public function decode(string $token): mixed;

    public function encode(array $claims): string;
}
