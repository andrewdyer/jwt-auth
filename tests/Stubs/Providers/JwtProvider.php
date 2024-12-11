<?php

namespace Anddye\JwtAuth\Tests\Stubs\Providers;

use Anddye\JwtAuth\Providers\JwtProviderInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtProvider implements JwtProviderInterface
{
    protected string $algorithm;

    protected string $secret;

    public function __construct()
    {
        $this->algorithm = 'HS256';
        $this->secret = 'I7s5cpLKGlK2tOY';
    }

    public function decode(string $token): mixed
    {
        return JWT::decode($token, new Key($this->secret, $this->algorithm));
    }

    public function encode(array $claims): string
    {
        return JWT::encode($claims, $this->secret, $this->algorithm);
    }
}
