<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Exceptions;

use RuntimeException;
use Throwable;

final class InvalidCredentialsException extends RuntimeException
{
    public function __construct(string $message = 'Invalid credentials provided.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
