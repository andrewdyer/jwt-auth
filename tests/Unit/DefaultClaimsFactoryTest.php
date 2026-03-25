<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Unit;

use AndrewDyer\JwtAuth\DefaultClaimsFactory;
use AndrewDyer\JwtAuth\Tests\Support\Clock;
use AndrewDyer\JwtAuth\Tests\Support\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DefaultClaimsFactoryTest extends TestCase
{
    public function testForSubjectBuildsClaimsWithExpectedValues(): void
    {
        $clock = new Clock(new DateTimeImmutable('@1000'));

        $factory = new DefaultClaimsFactory(
            clock: $clock,
            issuer: 'test-app',
            audience: 'test-aud',
            ttl: 3600,
            notBeforeGrace: 10,
        );

        $user = new User(123);

        $claims = $factory->forSubject($user);

        $this->assertSame('test-app', $claims->iss);
        $this->assertSame('test-aud', $claims->aud);
        $this->assertSame(1000, $claims->iat);
        $this->assertSame(1010, $claims->nbf);
        $this->assertSame(4600, $claims->exp);
        $this->assertSame(123, $claims->sub);

        $this->assertNotEmpty($claims->jti);
        $this->assertSame(32, strlen($claims->jti));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $claims->jti);
    }

    public function testForSubjectUsesDefaultConfigurationWhenOptionalArgumentsOmitted(): void
    {
        $clock = new Clock(new DateTimeImmutable('@5000'));
        $factory = new DefaultClaimsFactory(clock: $clock);

        $claims = $factory->forSubject(new User('abc'));

        $this->assertSame('app', $claims->iss);
        $this->assertNull($claims->aud);
        $this->assertSame(5000, $claims->iat);
        $this->assertSame(5000, $claims->nbf);
        $this->assertSame(8600, $claims->exp);
        $this->assertSame('abc', $claims->sub);

        $this->assertSame(32, strlen($claims->jti));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $claims->jti);
    }

    public function testForSubjectGeneratesUniqueJtiPerInvocation(): void
    {
        $clock = new Clock(new DateTimeImmutable('@2000'));
        $factory = new DefaultClaimsFactory(clock: $clock, ttl: 60);

        $claimsOne = $factory->forSubject(new User(1));
        $claimsTwo = $factory->forSubject(new User(2));

        $this->assertNotSame($claimsOne->jti, $claimsTwo->jti);

        $this->assertSame(32, strlen($claimsOne->jti));
        $this->assertSame(32, strlen($claimsTwo->jti));
    }

    public function testForSubjectHandlesZeroNotBeforeGrace(): void
    {
        $clock = new Clock(new DateTimeImmutable('@3000'));
        $factory = new DefaultClaimsFactory(clock: $clock, notBeforeGrace: 0, ttl: 120);

        $claims = $factory->forSubject(new User(42));

        $this->assertSame(3000, $claims->iat);
        $this->assertSame(3000, $claims->nbf, 'nbf should equal iat when grace is zero');
        $this->assertSame(3120, $claims->exp);
    }

    public function testForSubjectHandlesNegativeNotBeforeGrace(): void
    {
        $clock = new Clock(new DateTimeImmutable('@4000'));
        $factory = new DefaultClaimsFactory(clock: $clock, notBeforeGrace: -30, ttl: 90);

        $claims = $factory->forSubject(new User('neg'));

        $this->assertSame(4000, $claims->iat);
        $this->assertSame(3970, $claims->nbf, 'nbf should reflect negative grace offset');
        $this->assertSame(4090, $claims->exp);
    }

    public function testForSubjectRespectsShortTtl(): void
    {
        $clock = new Clock(new DateTimeImmutable('@1234'));
        $factory = new DefaultClaimsFactory(clock: $clock, ttl: 1);

        $claims = $factory->forSubject(new User('short'));

        $this->assertSame(1234, $claims->iat);
        $this->assertSame(1234, $claims->nbf);
        $this->assertSame(1235, $claims->exp);
    }
}
