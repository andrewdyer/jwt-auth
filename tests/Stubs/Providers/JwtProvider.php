<?php

namespace Anddye\JWTAuth\Tests\Stubs\Providers;

use Anddye\JWTAuth\Providers\JWTProviderInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTProvider implements JWTProviderInterface
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
