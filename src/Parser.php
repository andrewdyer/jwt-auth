<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Providers\JWTProviderInterface;

final class Parser
{
    protected JWTProviderInterface $jwtProvider;

    public function __construct(JWTProviderInterface $jwtProvider)
    {
        $this->jwtProvider = $jwtProvider;
    }

    public function decode(string $token)
    {
        return $this->jwtProvider->decode($token);
    }
}
