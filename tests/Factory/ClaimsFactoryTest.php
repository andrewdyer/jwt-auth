<?php

namespace Anddye\JWTAuth\Tests\Factory;

use Anddye\JWTAuth\Claims;
use Anddye\JWTAuth\Factory\ClaimsFactory;
use PHPUnit\Framework\TestCase;

final class ClaimsFactoryTest extends TestCase
{
    protected ClaimsFactory $claimsFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->claimsFactory = new ClaimsFactory();
    }

    public function testCanBuildClaimsInstance()
    {
        $claimsData = [
            'exp' => 1234567890,
            'iat' => 1234567890,
            'iss' => 'issuer',
            'jti' => 'jwt_id',
            'nbf' => 1234567890,
            'aud' => 'audience',
        ];

        $claims = ClaimsFactory::build($claimsData);

        $this->assertInstanceOf(Claims::class, $claims);
        $this->assertEquals(1234567890, $claims->getExp());
        $this->assertEquals(1234567890, $claims->getIat());
        $this->assertEquals('issuer', $claims->getIss());
        $this->assertEquals('jwt_id', $claims->getJti());
        $this->assertEquals(1234567890, $claims->getNbf());
        $this->assertEquals('audience', $claims->getAud());
    }
}
