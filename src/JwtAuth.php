<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth;

use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;
use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;
use AndrewDyer\JwtAuth\Exceptions\InvalidCredentialsException;
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

final readonly class JwtAuth
{
    public function __construct(
        private AuthProviderInterface $authProvider,
        private JwtProviderInterface $jwtProvider,
        private ClaimsFactoryInterface $claimsFactory,
    ) {
    }

    public function attempt(string $username, string $password): string
    {
        $user = $this->authProvider->byCredentials($username, $password);

        if (!$user instanceof JwtSubjectInterface) {
            throw new InvalidCredentialsException();
        }

        return $this->fromSubject($user);
    }

    public function authenticate(string $token): JwtSubjectInterface
    {
        $claims = $this->parse($token);

        $user = $this->authProvider->byId($claims->sub);

        if (!$user instanceof JwtSubjectInterface) {
            throw new InvalidTokenException();
        }

        return $user;
    }

    public function parse(string $token): Claims
    {
        $decoded = $this->jwtProvider->decode($token);

        if (!is_array($decoded) && !is_object($decoded)) {
            throw new InvalidTokenException();
        }

        return Claims::fromArray((array)$decoded);
    }

    public function refresh(string $token): string
    {
        $decoded = $this->jwtProvider->decodeUnverified($token);

        if (!is_object($decoded) || !isset($decoded->sub)) {
            throw new InvalidTokenException();
        }

        $user = $this->authProvider->byId($decoded->sub);

        if (!$user instanceof JwtSubjectInterface) {
            throw new InvalidTokenException();
        }

        return $this->fromSubject($user);
    }

    private function fromSubject(JwtSubjectInterface $subject): string
    {
        $claims = $this->claimsFactory->forSubject($subject);

        return $this->jwtProvider->encode($claims->toArray());
    }
}
