<?php

namespace Anddye\JwtAuth\Contracts;

interface JwtSubject
{
    public function getJwtIdentifier(): mixed;
}
