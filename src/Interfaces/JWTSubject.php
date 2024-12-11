<?php

namespace Anddye\JWTAuth\Interfaces;

interface JWTSubject
{
    public function getJWTIdentifier(): mixed;
}
