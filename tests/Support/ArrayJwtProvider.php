<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;

final class ArrayJwtProvider implements JwtProviderInterface
{
    public array $lastEncoded = [];

    public function decode(string $token): mixed
    {
        return json_decode(base64_decode($token));
    }

    public function encode(array $claims): string
    {
        $this->lastEncoded = $claims;

        return base64_encode(json_encode($claims));
    }
}
