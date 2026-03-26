<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

use AndrewDyer\JwtAuth\Claims;

interface ClaimsFactoryInterface
{
    public function forSubject(JwtSubjectInterface $subject): Claims;
}
