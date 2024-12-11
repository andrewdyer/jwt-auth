<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Contracts\JWTSubject;
use Anddye\JWTAuth\Providers\AuthProviderInterface;
use Anddye\JWTAuth\Providers\JWTProviderInterface;

final class JWTAuth
{
    protected ?JWTSubject $actor = null;

    protected AuthProviderInterface $authProvider;

    protected Factory $factory;

    protected Parser $parser;

    public function __construct(AuthProviderInterface $authProvider, JWTProviderInterface $jwtProvider, ClaimsFactory $claimsFactory)
    {
        $this->authProvider = $authProvider;
        $this->factory = new Factory($claimsFactory, $jwtProvider);
        $this->parser = new Parser($jwtProvider);
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
            $this->parser->decode($token)->sub
        );

        return $this;
    }

    public function getActor(): ?JWTSubject
    {
        return $this->actor;
    }

    protected function fromSubject(JWTSubject $subject): string
    {
        return $this->factory->encode($this->makePayload($subject));
    }

    protected function getClaimsForSubject(JWTSubject $subject): array
    {
        return [
            'sub' => $subject->getJWTIdentifier(),
        ];
    }

    protected function makePayload(JWTSubject $subject): array
    {
        return $this->factory->withClaims($this->getClaimsForSubject($subject))->make();
    }
}
