<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Thrown when authentication fails due to invalid or unrecognised credentials.
 *
 * Raised by the authentication layer when a username/password pair does not
 * match any known subject in the underlying data source.
 */
final class InvalidCredentialsException extends RuntimeException
{
    /**
     * @param string         $message  A human-readable description of the authentication failure.
     * @param int            $code     An application-specific error code.
     * @param Throwable|null $previous The previous exception, if this was thrown as part of a chain.
     */
    public function __construct(string $message = 'Invalid credentials provided.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
