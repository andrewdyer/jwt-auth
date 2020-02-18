<?php

namespace Anddye\JwtAuth\Tests\Integration;

use Anddye\JwtAuth\ClaimsFactory;
use Anddye\JwtAuth\Contracts\JwtSubject;
use Anddye\JwtAuth\JwtAuth;
use Anddye\JwtAuth\Tests\Stubs\Providers\AuthProvider;
use Anddye\JwtAuth\Tests\Stubs\Providers\JwtProvider;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * Class JwtAuthTest.
 */
final class JwtAuthTest extends TestCase
{
    /**
     * @var JwtAuth
     */
    protected $jwtAuth;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $authProvider = new AuthProvider();
        $jwtProvider = new JwtProvider();
        $claimsFactory = ClaimsFactory::build([
            'exp' => Carbon::now()->addMinute(5)->getTimestamp(),
            'iat' => Carbon::now()->getTimestamp(),
            'iss' => 'https://andrewdyer.rocks',
            'jti' => 'fVcx9BJHqh',
            'nbf' => Carbon::now()->getTimestamp(),
        ]);

        $this->jwtAuth = new JwtAuth($authProvider, $jwtProvider, $claimsFactory);
    }

    /**
     * @test
     */
    public function can_get_actor_with_valid_jwt_token()
    {
        $username = 'andrewdyer';
        $password = 'password';

        $token = $this->jwtAuth->attempt($username, $password);

        $actor = $this->jwtAuth->authenticate($token)->getActor();

        $this->assertNotNull($actor);
        $this->assertInstanceOf(JwtSubject::class, $actor);
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
