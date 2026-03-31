<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth;

use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

/**
 * Represents the decoded payload of a JWT, encapsulating all standard
 * registered claims alongside any custom (application-defined) claims.
 *
 * Instances are immutable; all properties are publicly readable but
 * cannot be modified after construction.
 */
final readonly class Claims
{
    /**
     * @param string               $iss    The issuer of the token — identifies the principal that issued the JWT.
     * @param string|null          $aud    The intended audience of the token, or null if unrestricted.
     * @param int                  $iat    Unix timestamp at which the token was issued.
     * @param int                  $nbf    Unix timestamp before which the token must not be accepted.
     * @param int                  $exp    Unix timestamp at which the token expires.
     * @param string               $jti    A unique token identifier, typically used to prevent replay attacks.
     * @param int|string           $sub    The subject of the token — the authenticated entity's unique identifier.
     * @param array<string, mixed> $custom Additional application-defined claims to include in the payload.
     */
    public function __construct(
        public string     $iss,
        public ?string    $aud,
        public int        $iat,
        public int        $nbf,
        public int        $exp,
        public string     $jti,
        public int|string $sub,
        public array      $custom = [],
    ) {
    }

    /**
     * Serialises the claims to an associative array suitable for JWT encoding.
     *
     * Standard claims are merged with any custom claims. Null values (such as
     * a missing audience) are omitted from the resulting array.
     *
     * @return array<string, mixed> The JWT payload as a key–value map.
     */
    public function toArray(): array
    {
        return array_filter(array_merge([
            'iss' => $this->iss,
            'aud' => $this->aud,
            'iat' => $this->iat,
            'nbf' => $this->nbf,
            'exp' => $this->exp,
            'jti' => $this->jti,
            'sub' => $this->sub,
        ], $this->custom), static fn ($v) => $v !== null);
    }

    /**
     * Constructs a Claims instance from a raw decoded JWT payload array.
     *
     * Validates that all required claims are present and that each carries the
     * expected type. Keys beyond the standard set are collected as custom claims
     * and preserved without modification.
     *
     * @param array<string, mixed> $data The raw payload array decoded from a JWT.
     *
     * @return self A fully populated and validated Claims instance.
     *
     * @throws InvalidTokenException If a required claim is absent or any claim value has an invalid type.
     */
    public static function fromArray(array $data): self
    {
        foreach (['iss', 'iat', 'nbf', 'exp', 'jti', 'sub'] as $key) {
            if (!array_key_exists($key, $data)) {
                throw new InvalidTokenException("Missing required claim: {$key}.");
            }
        }

        foreach (['iat', 'nbf', 'exp'] as $key) {
            if (!is_int($data[$key])) {
                throw new InvalidTokenException("Claim '{$key}' must be an integer.");
            }
        }

        if (!is_string($data['jti'])) {
            throw new InvalidTokenException("Claim 'jti' must be a string.");
        }

        if (!is_int($data['sub']) && !is_string($data['sub'])) {
            throw new InvalidTokenException("Claim 'sub' must be an integer or string.");
        }

        if (!is_string($data['iss'])) {
            throw new InvalidTokenException("Claim 'iss' must be a string.");
        }

        if (array_key_exists('aud', $data) && $data['aud'] !== null && !is_string($data['aud'])) {
            throw new InvalidTokenException("Claim 'aud' must be a string or null.");
        }

        return new self(
            iss: $data['iss'],
            aud: $data['aud'] ?? null,
            iat: $data['iat'],
            nbf: $data['nbf'],
            exp: $data['exp'],
            jti: $data['jti'],
            sub: $data['sub'],
            custom: array_diff_key($data, array_flip(['iss', 'aud', 'iat', 'nbf', 'exp', 'jti', 'sub']))
        );
    }
}
