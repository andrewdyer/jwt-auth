<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\ClockInterface;
use DateTimeImmutable;

final class Clock implements ClockInterface
{
    public function __construct(private DateTimeImmutable $now)
    {
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }

    public function advanceSeconds(int $seconds): void
    {
        $this->now = $this->now->modify("+{$seconds} seconds");
    }
}
