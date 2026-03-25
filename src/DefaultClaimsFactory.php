<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth;

use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\ClockInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

final readonly class DefaultClaimsFactory implements ClaimsFactoryInterface
{
    public function __construct(
        private ClockInterface $clock,
        private string         $issuer = 'app',
        private ?string        $audience = null,
        private int            $ttl = 3600,
        private int            $notBeforeGrace = 0,
    ) {
    }

    public function forSubject(JwtSubjectInterface $subject): Claims
    {
        $now = $this->clock->now()->getTimestamp();

        return new Claims(
            iss: $this->issuer,
            aud: $this->audience,
            iat: $now,
            nbf: $now + $this->notBeforeGrace,
            exp: $now + $this->ttl,
            jti: bin2hex(random_bytes(16)),
            sub: $subject->getJwtIdentifier(),
        );
    }
}
