<?php

namespace Anddye\JWTAuth\Contracts;

interface JWTSubject
{
    public function getJWTIdentifier(): mixed;
}
