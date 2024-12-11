<?php

namespace Anddye\JwtAuth\Tests\Unit;

use Anddye\JwtAuth\ClaimsFactory;
use Anddye\JwtAuth\Factory;
use Anddye\JwtAuth\Parser;
use Anddye\JwtAuth\Tests\Stubs\Providers\JwtProvider;
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

        $jwtProvider = new JwtProvider();

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
