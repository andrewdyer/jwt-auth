<?php

namespace Anddye\JWTAuth\Tests\Unit;

use Anddye\JWTAuth\ClaimsFactory;
use Anddye\JWTAuth\Factory;
use Anddye\JWTAuth\Parser;
use Anddye\JWTAuth\Tests\Stubs\Providers\JWTProvider;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    protected Factory $factory;

    protected Parser $parser;

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
        $this->parser = new Parser($jwtProvider);
    }

    /**
     * @test
     */
    public function can_decode_token()
    {
        $claims = $this->factory->make();
        $token = $this->factory->encode($claims);

        $payload = json_decode(json_encode($this->parser->decode($token)), true);

        $this->assertArrayHasKey('exp', $payload);
        $this->assertArrayHasKey('iat', $payload);
        $this->assertArrayHasKey('iss', $payload);
        $this->assertArrayHasKey('jti', $payload);
        $this->assertArrayHasKey('nbf', $payload);
    }
}
