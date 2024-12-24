<?php

namespace Anddye\JWTAuth\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Invalid credentials provided.';
}
