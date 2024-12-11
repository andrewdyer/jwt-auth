<?php

namespace Anddye\JwtAuth;

use Anddye\JwtAuth\Providers\JwtProviderInterface;

final class Parser
{
    protected JwtProviderInterface $jwtProvider;

    public function __construct(JwtProviderInterface $jwtProvider)
    {
        $this->jwtProvider = $jwtProvider;
    }

    public function decode(string $token)
    {
        return $this->jwtProvider->decode($token);
    }
}
