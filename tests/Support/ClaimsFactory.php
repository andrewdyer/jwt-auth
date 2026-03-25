<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Claims;
use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

final readonly class ClaimsFactory implements ClaimsFactoryInterface
{
    public function __construct(
        private int $now,
        private int $ttl = 3600,
    ) {
    }

    public function forSubject(JwtSubjectInterface $subject): Claims
    {
        return new Claims(
            iss: 'app',
            aud: null,
            iat: $this->now,
            nbf: $this->now,
            exp: $this->now + $this->ttl,
            jti: bin2hex(random_bytes(16)),
            sub: $subject->getJwtIdentifier(),
        );
    }
}
