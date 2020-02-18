<?php

namespace Anddye\JwtAuth\Contracts;

/**
 * Interface JwtSubject.
 */
interface JwtSubject
{
    /**
     * @return mixed
     */
    public function getJwtIdentifier();
}
