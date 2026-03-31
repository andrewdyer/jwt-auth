<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

/**
 * Defines the contract for resolving authenticated subjects from credentials or identifiers.
 *
 * Implementations are responsible for looking up users (or any JWT-capable entity)
 * from an underlying data source such as a database or in-memory store.
 */
interface AuthProviderInterface
{
    /**
     * Retrieves a subject by verifying the provided credentials.
     *
     * @param string $username The username or email to look up.
     * @param string $password The plain-text password to verify against the stored credential.
     *
     * @return JwtSubjectInterface|null The matching subject, or null if the credentials are invalid.
     */
    public function byCredentials(string $username, string $password): ?JwtSubjectInterface;

    /**
     * Retrieves a subject by their unique identifier.
     *
     * @param int|string $id The unique identifier of the subject to resolve.
     *
     * @return JwtSubjectInterface|null The matching subject, or null if no subject exists for the given ID.
     */
    public function byId(int|string $id): ?JwtSubjectInterface;
}
