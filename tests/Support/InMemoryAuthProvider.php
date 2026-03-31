<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

/**
 * Stub authentication provider for use in tests.
 *
 * Always returns the same preset subject regardless of the credentials or
 * identifier provided. Passing null allows simulating a failed lookup.
 */
final readonly class InMemoryAuthProvider implements AuthProviderInterface
{
    /**
     * @param JwtSubjectInterface|null $user The subject to return for every authentication attempt,
     *                                       or null to simulate a failed lookup.
     */
    public function __construct(
        private ?JwtSubjectInterface $user = null
    ) {
    }

    /**
     * Returns the preset subject regardless of the supplied credentials.
     *
     * @param string $username The username provided for authentication (ignored in this stub).
     * @param string $password The password provided for authentication (ignored in this stub).
     *
     * @return JwtSubjectInterface|null The preset subject, or null if none was configured.
     */
    public function byCredentials(string $username, string $password): ?JwtSubjectInterface
    {
        return $this->user;
    }

    /**
     * Returns the preset subject regardless of the supplied identifier.
     *
     * @param int|string $id The identifier used to look up a subject (ignored in this stub).
     *
     * @return JwtSubjectInterface|null The preset subject, or null if none was configured.
     */
    public function byId(int|string $id): ?JwtSubjectInterface
    {
        return $this->user;
    }
}
