<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Thrown when a JWT is malformed, missing required claims, or contains invalid claim values.
 *
 * Raised during token decoding or claim validation when the provided token cannot
 * be trusted or does not conform to the expected structure.
 */
final class InvalidTokenException extends RuntimeException
{
    /**
     * @param string         $message  A human-readable description of why the token was rejected.
     * @param int            $code     An application-specific error code.
     * @param Throwable|null $previous The previous exception, if this was thrown as part of a chain.
     */
    public function __construct(string $message = 'Invalid token provided.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
