<?php

namespace Anddye\JwtAuth;

use Anddye\JwtAuth\Providers\JwtProviderInterface;

/**
 * Class Factory.
 */
final class Factory
{
    /**
     * @var array
     */
    protected $claims = [];

    /**
     * @var ClaimsFactory
     */
    protected $claimsFactory;

    /**
     * @var JwtProviderInterface
     */
    protected $jwtProvider;

    /**
     * Factory constructor.
     *
     * @param ClaimsFactory        $claimsFactory
     * @param JwtProviderInterface $jwtProvider
     */
    public function __construct(ClaimsFactory $claimsFactory, JwtProviderInterface $jwtProvider)
    {
        $this->claimsFactory = $claimsFactory;
        $this->jwtProvider = $jwtProvider;
    }

    /**
     * @param array $claims
     *
     * @return string
     */
    public function encode(array $claims): string
    {
        return $this->jwtProvider->encode($claims);
    }

    /**
     * @return array
     */
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

    /**
     * @param array $claims
     *
     * @return $this
     */
    public function withClaims(array $claims): self
    {
        $this->claims = $claims;

        return $this;
    }
}
