<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Interfaces\AuthProviderInterface;
use Anddye\JWTAuth\Interfaces\ClaimsInterface;
use Anddye\JWTAuth\Interfaces\JWTProviderInterface;
use Anddye\JWTAuth\Interfaces\JWTSubject;

final class JWTAuth
{
    protected ?JWTSubject $actor = null;

    protected AuthProviderInterface $authProvider;

    protected ClaimsInterface $claims;

    protected JWTProviderInterface $jwtProvider;

    protected array $claimsData = [];

    public function __construct(AuthProviderInterface $authProvider, JWTProviderInterface $jwtProvider, ClaimsInterface $claims)
    {
        $this->authProvider = $authProvider;
        $this->claims = $claims;
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
        $claims['exp'] = $this->claims->getExp();
        $claims['iat'] = $this->claims->getIat();
        $claims['iss'] = $this->claims->getIss();
        $claims['jti'] = $this->claims->getJti();
        $claims['nbf'] = $this->claims->getNbf();

        return array_merge($this->claimsData, $claims);
    }

    protected function withClaims(array $claims): self
    {
        $this->claimsData = $claims;

        return $this;
    }
}
