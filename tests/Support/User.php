<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

/**
 * Minimal JwtSubjectInterface implementation representing a test user.
 *
 * Used across unit tests to provide a concrete subject without the overhead
 * of a full domain model or database-backed entity.
 */
final readonly class User implements JwtSubjectInterface
{
    /**
     * @param int|string $id The unique identifier for this user, used as the JWT subject claim.
     */
    public function __construct(private int|string $id)
    {
    }

    /**
     * Returns the user's unique identifier for use as the `sub` claim in a JWT.
     *
     * @return int|string The user's identifier.
     */
    public function getJwtIdentifier(): int|string
    {
        return $this->id;
    }
}
