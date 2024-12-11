<?php

namespace Anddye\JwtAuth;

use Anddye\JwtAuth\Contracts\JwtSubject;
use Anddye\JwtAuth\Providers\AuthProviderInterface;
use Anddye\JwtAuth\Providers\JwtProviderInterface;

final class JwtAuth
{
    protected ?JwtSubject $actor = null;

    protected AuthProviderInterface $authProvider;

    protected Factory $factory;

    protected Parser $parser;

    public function __construct(AuthProviderInterface $authProvider, JwtProviderInterface $jwtProvider, ClaimsFactory $claimsFactory)
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

    public function getActor(): ?JwtSubject
    {
        return $this->actor;
    }

    protected function fromSubject(JwtSubject $subject): string
    {
        return $this->factory->encode($this->makePayload($subject));
    }

    protected function getClaimsForSubject(JwtSubject $subject): array
    {
        return [
            'sub' => $subject->getJwtIdentifier(),
        ];
    }

    protected function makePayload(JwtSubject $subject): array
    {
        return $this->factory->withClaims($this->getClaimsForSubject($subject))->make();
    }
}
