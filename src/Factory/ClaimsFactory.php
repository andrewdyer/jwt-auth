<?php

namespace Anddye\JWTAuth\Factory;

use Anddye\JWTAuth\Claims;

class ClaimsFactory
{
    public static function build(array $claims): Claims
    {
        $claimsInstance = new Claims();

        if (isset($claims['exp'])) {
            $claimsInstance->setExp($claims['exp']);
        }

        if (isset($claims['iat'])) {
            $claimsInstance->setIat($claims['iat']);
        }

        if (isset($claims['iss'])) {
            $claimsInstance->setIss($claims['iss']);
        }

        if (isset($claims['jti'])) {
            $claimsInstance->setJti($claims['jti']);
        }

        if (isset($claims['nbf'])) {
            $claimsInstance->setNbf($claims['nbf']);
        }

        return $claimsInstance;
    }
}
