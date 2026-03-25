<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DateTimeImmutable;
}
