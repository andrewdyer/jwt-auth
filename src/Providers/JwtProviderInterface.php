<?php

namespace Anddye\JwtAuth\Providers;

/**
 * Interface JwtProviderInterface.
 */
interface JwtProviderInterface
{
    /**
     * @param string $token
     *
     * @return mixed
     */
    public function decode(string $token);

    /**
     * @param array $claims
     *
     * @return string
     */
    public function encode(array $claims): string;
}
