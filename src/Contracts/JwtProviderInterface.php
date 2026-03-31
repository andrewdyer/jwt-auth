<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

/**
 * Defines the contract for encoding and decoding JWT strings.
 *
 * Implementations wrap a concrete JWT library and expose a normalised interface
 * so the rest of the package remains decoupled from any specific signing
 * algorithm or third-party dependency.
 */
interface JwtProviderInterface
{
    /**
     * Decodes a JWT string and returns its payload.
     *
     * @param string $token The JWT string to decode.
     *
     * @return mixed The decoded payload, typically an associative array or stdClass object.
     */
    public function decode(string $token): mixed;

    /**
     * Encodes a claims array as a JWT string.
     *
     * @param array<string, mixed> $claims The claims payload to encode.
     *
     * @return string A JWT string representing the provided claims.
     */
    public function encode(array $claims): string;
}
