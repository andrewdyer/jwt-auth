<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Unit;

use AndrewDyer\JwtAuth\Claims;
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;
use PHPUnit\Framework\TestCase;

final class ClaimsTest extends TestCase
{
    public function testToArrayReturnsExpectedClaims(): void
    {
        $claims = new Claims(
            iss: 'app',
            aud: 'web',
            iat: 1000,
            nbf: 1000,
            exp: 2000,
            jti: 'abc',
            sub: 1,
            custom: ['role' => 'admin']
        );

        $array = $claims->toArray();

        $this->assertSame('app', $array['iss']);
        $this->assertSame('web', $array['aud']);
        $this->assertSame(1000, $array['iat']);
        $this->assertSame(2000, $array['exp']);
        $this->assertSame('admin', $array['role']);
    }

    public function testToArrayOmitsNullValuesAndPreservesTypes(): void
    {
        $claims = new Claims(
            iss: 'app',
            aud: null,
            iat: 1000,
            nbf: 1000,
            exp: 2000,
            jti: 'abc',
            sub: 1,
            custom: ['views' => 10]
        );

        $array = $claims->toArray();

        $this->assertArrayNotHasKey('aud', $array);
        $this->assertSame(1, $array['sub']);
        $this->assertSame(10, $array['views']);
    }

    public function testToArrayAllowsCustomClaimsToOverrideDefaults(): void
    {
        $claims = new Claims(
            iss: 'app',
            aud: 'web',
            iat: 1000,
            nbf: 1000,
            exp: 2000,
            jti: 'abc',
            sub: 1,
            custom: [
                'role' => 'admin',
                'iss' => 'override-app',
            ]
        );

        $array = $claims->toArray();

        $this->assertSame('override-app', $array['iss']);
        $this->assertSame('admin', $array['role']);
    }

    public function testFromArrayAppliesDefaultsAndExtractsCustomClaims(): void
    {
        $claims = Claims::fromArray([
            'iss' => 'app',
            'iat' => 1000,
            'nbf' => 1000,
            'exp' => 2000,
            'jti' => 'abc',
            'sub' => 1,
            'role' => 'admin',
            'feature' => 'beta',
        ]);

        $this->assertSame('app', $claims->iss);
        $this->assertNull($claims->aud);
        $this->assertSame(1000, $claims->iat);
        $this->assertSame(1000, $claims->nbf);
        $this->assertSame(2000, $claims->exp);
        $this->assertSame('abc', $claims->jti);
        $this->assertSame(1, $claims->sub);

        $this->assertSame([
            'role' => 'admin',
            'feature' => 'beta',
        ], $claims->custom);
    }

    public function testFromArrayPreservesSubjectDataType(): void
    {
        $claims = Claims::fromArray([
            'iss' => 'service',
            'aud' => 'mobile',
            'iat' => 1000,
            'nbf' => 1000,
            'exp' => 2000,
            'jti' => 'abc',
            'sub' => 'user-123',
        ]);

        $this->assertSame('user-123', $claims->sub);
        $this->assertSame('service', $claims->iss);
        $this->assertSame('mobile', $claims->aud);
    }

    public function testClaimsCanBeRoundTrippedThroughArray(): void
    {
        $claims = new Claims(
            iss: 'api',
            aud: 'mobile',
            iat: 1000,
            nbf: 1000,
            exp: 2000,
            jti: 'abc',
            sub: 99,
            custom: ['role' => 'moderator', 'tier' => 'gold']
        );

        $array = $claims->toArray();
        $rebuilt = Claims::fromArray($array);

        $this->assertSame($claims->iss, $rebuilt->iss);
        $this->assertSame($claims->aud, $rebuilt->aud);
        $this->assertSame($claims->iat, $rebuilt->iat);
        $this->assertSame($claims->nbf, $rebuilt->nbf);
        $this->assertSame($claims->exp, $rebuilt->exp);
        $this->assertSame($claims->jti, $rebuilt->jti);
        $this->assertSame($claims->sub, $rebuilt->sub);
        $this->assertSame($claims->custom, $rebuilt->custom);
    }

    /**
     * @dataProvider missingRequiredClaimProvider
     */
    public function testFromArrayThrowsOnMissingRequiredClaim(array $data): void
    {
        $this->expectException(InvalidTokenException::class);

        Claims::fromArray($data);
    }

    public static function missingRequiredClaimProvider(): array
    {
        $base = ['iss' => 'app', 'iat' => 1000, 'nbf' => 1000, 'exp' => 2000, 'jti' => 'abc', 'sub' => 1];

        return [
            'missing iss' => [array_diff_key($base, ['iss' => true])],
            'missing iat' => [array_diff_key($base, ['iat' => true])],
            'missing nbf' => [array_diff_key($base, ['nbf' => true])],
            'missing exp' => [array_diff_key($base, ['exp' => true])],
            'missing jti' => [array_diff_key($base, ['jti' => true])],
            'missing sub' => [array_diff_key($base, ['sub' => true])],
        ];
    }

    /**
     * @dataProvider invalidClaimTypeProvider
     */
    public function testFromArrayThrowsOnInvalidClaimType(array $data): void
    {
        $this->expectException(InvalidTokenException::class);

        Claims::fromArray($data);
    }

    public static function invalidClaimTypeProvider(): array
    {
        $base = ['iss' => 'app', 'iat' => 1000, 'nbf' => 1000, 'exp' => 2000, 'jti' => 'abc', 'sub' => 1];

        return [
            'iat not int' => [array_merge($base, ['iat' => '1000'])],
            'nbf not int' => [array_merge($base, ['nbf' => '1000'])],
            'exp not int' => [array_merge($base, ['exp' => '2000'])],
            'jti not string' => [array_merge($base, ['jti' => 123])],
            'sub invalid type' => [array_merge($base, ['sub' => 1.5])],
            'iss not string' => [array_merge($base, ['iss' => 42])],
            'aud not string' => [array_merge($base, ['aud' => 99])],
        ];
    }
}
