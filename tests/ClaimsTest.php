<?php

namespace Anddye\JWTAuth\Tests;

use Anddye\JWTAuth\Claims;
use PHPUnit\Framework\TestCase;

class ClaimsTest extends TestCase
{
    public function testSetAndGetExp()
    {
        $claims = new Claims();
        $claims->setExp(1234567890);
        $this->assertEquals(1234567890, $claims->getExp());
    }

    public function testSetAndGetIat()
    {
        $claims = new Claims();
        $claims->setIat(1234567890);
        $this->assertEquals(1234567890, $claims->getIat());
    }

    public function testSetAndGetIss()
    {
        $claims = new Claims();
        $claims->setIss('issuer');
        $this->assertEquals('issuer', $claims->getIss());
    }

    public function testSetAndGetJti()
    {
        $claims = new Claims();
        $claims->setJti('jwt_id');
        $this->assertEquals('jwt_id', $claims->getJti());
    }

    public function testSetAndGetNbf()
    {
        $claims = new Claims();
        $claims->setNbf(1234567890);
        $this->assertEquals(1234567890, $claims->getNbf());
    }
}
