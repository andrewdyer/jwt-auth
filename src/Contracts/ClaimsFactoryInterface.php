<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth\Contracts;

use AndrewDyer\JwtAuth\Claims;

/**
 * Defines the contract for constructing a JWT claims payload for a given subject.
 *
 * Implementations control which claims are included in the token, covering
 * standard registered claims (issuer, expiry, etc.) and any application-specific additions.
 */
interface ClaimsFactoryInterface
{
    /**
     * Builds a Claims instance populated for the given JWT subject.
     *
     * @param JwtSubjectInterface $subject The entity for whom the token is being issued.
     *
     * @return Claims A fully populated Claims object ready for encoding.
     */
    public function forSubject(JwtSubjectInterface $subject): Claims;
}
