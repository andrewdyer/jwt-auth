<?php

namespace Anddye\JWTAuth\Tests\Integration;

use Anddye\JWTAuth\Factory\ClaimsFactory;
use Anddye\JWTAuth\JWTAuth;
use Anddye\JWTAuth\Tests\Stubs\Providers\AuthProvider;
use Anddye\JWTAuth\Tests\Stubs\Providers\JWTProvider;
use Carbon\Carbon;
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
            'exp' => Carbon::now()->addMinute(5)->getTimestamp(),
            'iat' => Carbon::now()->getTimestamp(),
            'iss' => 'https://andrewdyer.rocks',
            'jti' => 'fVcx9BJHqh',
            'nbf' => Carbon::now()->getTimestamp(),
        ]);

        $this->jwtAuth = new JWTAuth($authProvider, $jwtProvider, $claimsFactory);
    }

    /**
     * @test
     */
    public function can_get_token_with_valid_login_credentials()
    {
        $username = 'andrewdyer';
        $password = 'password';

        $token = $this->jwtAuth->attempt($username, $password);

        $this->assertIsString($token);
    }

    /**
     * @test
     */
    public function cant_get_token_with_incorrect_login_credentials()
    {
        $username = 'andrewdyer';
        $password = 'pa55w0rd';

        $token = $this->jwtAuth->attempt($username, $password);

        $this->assertNull($token);
    }
}
