<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Providers\JWTProviderInterface;

final class Factory
{
    protected array $claims = [];

    protected ClaimsFactory $claimsFactory;

    protected JWTProviderInterface $jwtProvider;

    public function __construct(ClaimsFactory $claimsFactory, JWTProviderInterface $jwtProvider)
    {
        $this->claimsFactory = $claimsFactory;
        $this->jwtProvider = $jwtProvider;
    }

    public function encode(array $claims): string
    {
        return $this->jwtProvider->encode($claims);
    }

    public function make(): array
    {
        $claims = [];
        $claims['exp'] = $this->claimsFactory->getExp();
        $claims['iat'] = $this->claimsFactory->getIat();
        $claims['iss'] = $this->claimsFactory->getIss();
        $claims['jti'] = $this->claimsFactory->getJti();
        $claims['nbf'] = $this->claimsFactory->getNbf();

        return array_merge($this->claims, $claims);
    }

    public function withClaims(array $claims): self
    {
        $this->claims = $claims;

        return $this;
    }
}
