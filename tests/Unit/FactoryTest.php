<?php

namespace Anddye\JWTAuth\Tests\Unit;

use Anddye\JWTAuth\ClaimsFactory;
use Anddye\JWTAuth\Factory;
use Anddye\JWTAuth\Tests\Stubs\Providers\JWTProvider;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

final class FactoryTest extends TestCase
{
    protected Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $claimsFactory = ClaimsFactory::build([
            'exp' => Carbon::now()->addMinute(5)->getTimestamp(),
            'iat' => Carbon::now()->getTimestamp(),
            'iss' => 'https://andrewdyer.rocks',
            'jti' => 'fVcx9BJHqh',
            'nbf' => Carbon::now()->getTimestamp(),
        ]);

        $jwtProvider = new JWTProvider();

        $this->factory = new Factory($claimsFactory, $jwtProvider);
    }

    /**
     * @test
     */
    public function can_encode_array_of_claims()
    {
        $claims = $this->factory->make();

        $string = $this->factory->encode($claims);

        $this->assertIsString($string);
    }

    /**
     * @test
     */
    public function can_make_claims()
    {
        $claims = $this->factory->make();

        $this->assertIsArray($claims);
    }

    /**
     * @test
     */
    public function can_make_claims_with_defaults()
    {
        $claims = $this->factory->withClaims([
            'aud' => 'guest',
        ])->make();

        $this->assertIsArray($claims);
    }
}
