<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Exceptions;

use RuntimeException;

final class InvalidTokenException extends RuntimeException
{
    protected $message = 'Invalid token provided.';
}
