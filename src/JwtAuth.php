<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Contracts\JWTSubject;
use Anddye\JWTAuth\Factory\ClaimsFactory;
use Anddye\JWTAuth\Providers\AuthProviderInterface;
use Anddye\JWTAuth\Providers\JWTProviderInterface;

final class JWTAuth
{
    protected ?JWTSubject $actor = null;

    protected AuthProviderInterface $authProvider;

    protected ClaimsFactory $claimsFactory;

    protected JWTProviderInterface $jwtProvider;

    protected array $claims = [];

    public function __construct(AuthProviderInterface $authProvider, JWTProviderInterface $jwtProvider, ClaimsFactory $claimsFactory)
    {
        $this->authProvider = $authProvider;
        $this->claimsFactory = $claimsFactory;
        $this->jwtProvider = $jwtProvider;
    }

    public function attempt(string $username, string $password): ?string
    {
        if (!$user = $this->authProvider->byCredentials($username, $password)) {
            return null;
        }

        return $this->fromSubject($user);
    }

    public function authenticate(string $token): self
    {
        $this->actor = $this->authProvider->byId(
            $this->decode($token)->sub
        );

        return $this;
    }

    public function getActor(): ?JWTSubject
    {
        return $this->actor;
    }

    protected function fromSubject(JWTSubject $subject): string
    {
        return $this->encode($this->makePayload($subject));
    }

    protected function getClaimsForSubject(JWTSubject $subject): array
    {
        return [
            'sub' => $subject->getJWTIdentifier(),
        ];
    }

    protected function makePayload(JWTSubject $subject): array
    {
        return $this->withClaims($this->getClaimsForSubject($subject))->make();
    }

    protected function decode(string $token): mixed
    {
        return $this->jwtProvider->decode($token);
    }

    protected function encode(array $claims): string
    {
        return $this->jwtProvider->encode($claims);
    }

    protected function make(): array
    {
        $claims = [];
        $claims['exp'] = $this->claimsFactory->getExp();
        $claims['iat'] = $this->claimsFactory->getIat();
        $claims['iss'] = $this->claimsFactory->getIss();
        $claims['jti'] = $this->claimsFactory->getJti();
        $claims['nbf'] = $this->claimsFactory->getNbf();

        return array_merge($this->claims, $claims);
    }

    protected function withClaims(array $claims): self
    {
        $this->claims = $claims;

        return $this;
    }
}
