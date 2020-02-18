<?php

namespace Anddye\JwtAuth;

use Anddye\JwtAuth\Providers\JwtProviderInterface;

/**
 * Class Parser.
 */
final class Parser
{
    /**
     * @var JwtProviderInterface
     */
    protected $jwtProvider;

    /**
     * Parser constructor.
     *
     * @param JwtProviderInterface $jwtProvider
     */
    public function __construct(JwtProviderInterface $jwtProvider)
    {
        $this->jwtProvider = $jwtProvider;
    }

    /**
     * @param string $token
     *
     * @return mixed
     */
    public function decode(string $token)
    {
        return $this->jwtProvider->decode($token);
    }
}
