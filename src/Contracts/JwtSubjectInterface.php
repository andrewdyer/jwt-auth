<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

interface JwtSubjectInterface
{
    public function getJwtIdentifier(): int|string;
}
