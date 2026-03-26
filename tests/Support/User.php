<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Tests\Support;

use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;

final readonly class User implements JwtSubjectInterface
{
    public function __construct(private int|string $id)
    {
    }

    public function getJwtIdentifier(): int|string
    {
        return $this->id;
    }
}
