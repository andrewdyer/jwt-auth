<?php

namespace Anddye\JWTAuth;

use Anddye\JWTAuth\Interfaces\AuthProviderInterface;
use Anddye\JWTAuth\Interfaces\ClaimsInterface;
use Anddye\JWTAuth\Interfaces\JWTProviderInterface;
use Anddye\JWTAuth\Interfaces\JWTSubject;

final class JWTAuth
{
    protected AuthProviderInterface $authProvider;

    protected ClaimsInterface $claims;

    protected JWTProviderInterface $jwtProvider;

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

    public function authenticate(string $token): JWTSubject
    {
        $decoded = $this->decode($token);

        return $this->authProvider->byId($decoded->sub);
    }

    protected function fromSubject(JWTSubject $subject): string
    {
        $this->claims->setSub($subject);

        return $this->encode($this->makePayload());
    }

    protected function makePayload(): array
    {
        return [
            'exp' => $this->claims->getExp(),
            'iat' => $this->claims->getIat(),
            'iss' => $this->claims->getIss(),
            'jti' => $this->claims->getJti(),
            'nbf' => $this->claims->getNbf(),
            'sub' => $this->claims->getSub()->getJWTIdentifier(),
        ];
    }

    protected function decode(string $token): mixed
    {
        return $this->jwtProvider->decode($token);
    }

    protected function encode(array $claims): string
    {
        return $this->jwtProvider->encode($claims);
    }
}
