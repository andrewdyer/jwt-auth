<?php

namespace Anddye\JwtAuth\Tests\Stubs\Providers;

use Anddye\JwtAuth\Providers\JwtProviderInterface;
use Firebase\JWT\JWT;

/**
 * Class JwtProvider.
 */
class JwtProvider implements JwtProviderInterface
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @var string
     */
    protected $secret;

    /**
     * JwtProvider constructor.
     */
    public function __construct()
    {
        $this->algorithm = 'HS256';
        $this->secret = 'I7s5cpLKGlK2tOY';
    }

    /**
     * @param string $token
     *
     * @return mixed
     */
    public function decode(string $token)
    {
        return JWT::decode($token, $this->secret, [$this->algorithm]);
    }

    /**
     * @param array $claims
     *
     * @return string
     */
    public function encode(array $claims): string
    {
        return JWT::encode($claims, $this->secret, $this->algorithm);
    }
}
