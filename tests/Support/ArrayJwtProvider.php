<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;

/**
 * In-memory JWT provider for use in tests.
 *
 * Tokens are not cryptographically signed; the claims payload is serialised
 * as base64-encoded JSON. This keeps tests fast and free from signature
 * configuration while still exercising the full encode/decode flow.
 */
final class ArrayJwtProvider implements JwtProviderInterface
{
    /**
     * The claims array from the most recent call to encode().
     *
     * Exposed publicly so tests can assert on the exact payload that was encoded.
     *
     * @var array<string, mixed>
     */
    public array $lastEncoded = [];

    /**
     * Decodes a base64-encoded JSON token and returns its payload as a plain object.
     *
     * @param string $token The base64-encoded JSON string to decode.
     *
     * @return mixed The decoded payload, typically a stdClass object.
     */
    public function decode(string $token): mixed
    {
        return json_decode(base64_decode($token));
    }

    /**
     * Encodes a claims array as a base64-encoded JSON string and stores it for later inspection.
     *
     * @param array<string, mixed> $claims The claims payload to encode.
     *
     * @return string A base64-encoded JSON representation of the provided claims.
     */
    public function encode(array $claims): string
    {
        $this->lastEncoded = $claims;

        return base64_encode(json_encode($claims));
    }
}
