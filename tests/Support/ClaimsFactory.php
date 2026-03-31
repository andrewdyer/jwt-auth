<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Claims;
use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

/**
 * Test-only claims factory that produces a deterministic Claims object for a given subject.
 *
 * Time values are injected via the constructor, making token expiry fully predictable
 * in tests without relying on the system clock.
 */
final readonly class ClaimsFactory implements ClaimsFactoryInterface
{
    /**
     * @param int $now The Unix timestamp used as both the issued-at (iat) and not-before (nbf) claims.
     * @param int $ttl The token lifetime in seconds, added to $now to derive the expiry (exp) claim.
     */
    public function __construct(
        private int $now,
        private int $ttl = 3600,
    ) {
    }

    /**
     * Constructs a Claims instance for the given subject using the factory's fixed timestamps.
     *
     * A random JTI is generated on each call to ensure token uniqueness across
     * multiple invocations within the same test scenario.
     *
     * @param JwtSubjectInterface $subject The entity for whom the token is being issued.
     *
     * @return Claims A fully populated Claims object with deterministic time values.
     */
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
