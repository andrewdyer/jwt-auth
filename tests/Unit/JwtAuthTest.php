<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Unit;

use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;
use AndrewDyer\JwtAuth\Exceptions\InvalidCredentialsException;
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;
use AndrewDyer\JwtAuth\JwtAuth;
use AndrewDyer\JwtAuth\Tests\Support\ArrayJwtProvider;
use AndrewDyer\JwtAuth\Tests\Support\ClaimsFactory;
use AndrewDyer\JwtAuth\Tests\Support\InMemoryAuthProvider;
use AndrewDyer\JwtAuth\Tests\Support\User;
use PHPUnit\Framework\TestCase;

final class JwtAuthTest extends TestCase
{
    public function testAttemptReturnsEncodedTokenForValidCredentials(): void
    {
        $user = new User(1);

        $authProvider = new InMemoryAuthProvider($user);
        $jwtProvider = new ArrayJwtProvider();
        $claimsFactory = new ClaimsFactory(now: 1000);

        $auth = new JwtAuth(
            authProvider: $authProvider,
            jwtProvider: $jwtProvider,
            claimsFactory: $claimsFactory,
        );

        $token = $auth->attempt('user', 'pass');

        $this->assertIsString($token);
        $this->assertSame(1, $jwtProvider->lastEncoded['sub']);
        $this->assertSame(1000, $jwtProvider->lastEncoded['iat']);
    }

    public function testAttemptThrowsInvalidCredentialsExceptionForInvalidCredentials(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $auth = new JwtAuth(
            authProvider: new InMemoryAuthProvider(null),
            jwtProvider: new ArrayJwtProvider(),
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $auth->attempt('bad', 'creds');
    }

    public function testAuthenticateReturnsUserForValidToken(): void
    {
        $user = new User(42);

        $authProvider = new InMemoryAuthProvider($user);
        $jwtProvider = new ArrayJwtProvider();

        $claims = [
            'sub' => 42,
            'iat' => 1000,
            'nbf' => 1000,
            'exp' => 2000,
            'jti' => 'abc',
            'iss' => 'app',
        ];
        $token = base64_encode(json_encode($claims));

        $auth = new JwtAuth(
            authProvider: $authProvider,
            jwtProvider: $jwtProvider,
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $result = $auth->authenticate($token);

        $this->assertSame($user, $result);
    }

    public function testAuthenticateThrowsInvalidTokenExceptionWhenUserNotFound(): void
    {
        $auth = new JwtAuth(
            authProvider: new InMemoryAuthProvider(null),
            jwtProvider: new ArrayJwtProvider(),
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $claims = [
            'sub' => 99,
            'iat' => 1000,
            'nbf' => 1000,
            'exp' => 2000,
            'jti' => 'abc',
            'iss' => 'app',
        ];
        $token = base64_encode(json_encode($claims));

        $this->expectException(InvalidTokenException::class);

        $auth->authenticate($token);
    }

    public function testParseReturnsClaimsFromArrayPayload(): void
    {
        $jwtProvider = new class () implements JwtProviderInterface {
            public function decode(string $token): mixed
            {
                return [
                    'sub' => 7,
                    'iat' => 10,
                    'nbf' => 10,
                    'exp' => 20,
                    'jti' => 'arr',
                    'iss' => 'api',
                    'role' => 'admin',
                ];
            }

            public function encode(array $claims): string
            {
                return '';
            }
        };

        $auth = new JwtAuth(
            authProvider: new InMemoryAuthProvider(),
            jwtProvider: $jwtProvider,
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $claims = $auth->parse('any');

        $this->assertSame(7, $claims->sub);
        $this->assertSame('admin', $claims->custom['role']);
    }

    public function testParseReturnsClaimsFromObjectPayload(): void
    {
        $payload = (object)[
            'sub' => 8,
            'iat' => 11,
            'nbf' => 11,
            'exp' => 21,
            'jti' => 'obj',
            'iss' => 'api',
            'scope' => 'read',
        ];

        $token = base64_encode(json_encode($payload));

        $auth = new JwtAuth(
            authProvider: new InMemoryAuthProvider(),
            jwtProvider: new ArrayJwtProvider(),
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $claims = $auth->parse($token);

        $this->assertSame(8, $claims->sub);
        $this->assertSame('read', $claims->custom['scope']);
    }

    public function testParseThrowsInvalidTokenExceptionForNonObjectOrArrayPayload(): void
    {
        $auth = new JwtAuth(
            authProvider: new InMemoryAuthProvider(),
            jwtProvider: new ArrayJwtProvider(),
            claimsFactory: $this->createMock(ClaimsFactoryInterface::class),
        );

        $token = base64_encode(json_encode('not-an-object'));

        $this->expectException(InvalidTokenException::class);

        $auth->parse($token);
    }
}
