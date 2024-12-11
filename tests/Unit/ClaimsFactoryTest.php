<?php

namespace Anddye\JWTAuth\Tests\Unit;

use Anddye\JWTAuth\Factory\ClaimsFactory;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

final class ClaimsFactoryTest extends TestCase
{
    protected ClaimsFactory $claimsFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->claimsFactory = new ClaimsFactory();
    }

    /**
     * @test
     */
    public function can_set_expiration_time_claim()
    {
        $timestamp = Carbon::now()->getTimestamp();

        $this->claimsFactory->setExp($timestamp);

        $this->assertEquals($timestamp, $this->claimsFactory->getExp());
    }

    /**
     * @test
     */
    public function can_set_issued_at_claim()
    {
        $timestamp = Carbon::now()->getTimestamp();

        $this->claimsFactory->setIat($timestamp);

        $this->assertEquals($timestamp, $this->claimsFactory->getIat());
    }

    /**
     * @test
     */
    public function can_set_issuer_claim()
    {
        $issuer = 'https://andrewdyer.rocks';

        $this->claimsFactory->setIss($issuer);

        $this->assertEquals($issuer, $this->claimsFactory->getIss());
    }

    /**
     * @test
     */
    public function can_set_jwt_id_claim()
    {
        $jwtId = 'fVcx9BJHqh';

        $this->claimsFactory->setJti($jwtId);

        $this->assertEquals($jwtId, $this->claimsFactory->getJti());
    }

    /**
     * @test
     */
    public function can_set_not_before_claim()
    {
        $timestamp = Carbon::now()->getTimestamp();

        $this->claimsFactory->setNbf($timestamp);

        $this->assertEquals($timestamp, $this->claimsFactory->getNbf());
    }
}
