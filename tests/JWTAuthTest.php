<?php

namespace Anddye\JWTAuth\Tests;

use Anddye\JWTAuth\Factory\ClaimsFactory;
use Anddye\JWTAuth\JWTAuth;
use Anddye\JWTAuth\Tests\Stubs\Providers\AuthProvider;
use Anddye\JWTAuth\Tests\Stubs\Providers\JWTProvider;
use PHPUnit\Framework\TestCase;

final class JWTAuthTest extends TestCase
{
    protected JWTAuth $jwtAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $authProvider = new AuthProvider();
        $jwtProvider = new JWTProvider();
        $claimsFactory = ClaimsFactory::build([
            'iss' => 'https://andrewdyer.rocks',
            'aud' => 'https://andrewdyer.rocks',
            'exp' => 1234567890,
            'nbf' => 1234567890,
            'iat' => 1234567890,
            'jti' => 'fVcx9BJHqh',
        ]);

        $this->jwtAuth = new JWTAuth($authProvider, $jwtProvider, $claimsFactory);
    }

    public function testCanGetTokenWithValidLoginCredentials()
    {
        $username = 'andrewdyer';
        $password = 'password';

        $token = $this->jwtAuth->attempt($username, $password);

        $this->assertIsString($token);
    }

    public function testCantGetTokenWithIncorrectLoginCredentials()
    {
        $username = 'andrewdyer';
        $password = 'pa55w0rd';

        $token = $this->jwtAuth->attempt($username, $password);

        $this->assertNull($token);
    }
}
