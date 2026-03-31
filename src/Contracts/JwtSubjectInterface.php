<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

/**
 * Defines the contract for entities that can be represented as the subject of a JWT.
 *
 * Any model or entity that participates in JWT authentication must implement
 * this interface so that its identifier can be embedded as the `sub` claim.
 */
interface JwtSubjectInterface
{
    /**
     * Returns the unique identifier used as the `sub` claim in the JWT.
     *
     * @return int|string The subject identifier — typically a user ID or UUID.
     */
    public function getJwtIdentifier(): int|string;
}
