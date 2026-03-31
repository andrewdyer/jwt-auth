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
     * Returns null if the input is not valid base64, which causes JwtAuth::parse()
     * to raise InvalidTokenException deterministically.
     *
     * @param string $token The base64-encoded JSON string to decode.
     *
     * @return mixed The decoded payload as a stdClass object, or null on invalid input.
     */
    public function decode(string $token): mixed
    {
        $decoded = base64_decode($token, true);

        if ($decoded === false) {
            return null;
        }

        return json_decode($decoded);
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
