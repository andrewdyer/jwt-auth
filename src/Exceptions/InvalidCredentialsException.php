<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Exceptions;

use RuntimeException;

final class InvalidCredentialsException extends RuntimeException
{
    protected $message = 'Invalid credentials provided.';
}
