<?php

declare(strict_types=1);

namespace AndrewDyer\JwtAuth;

use AndrewDyer\JwtAuth\Contracts\AuthProviderInterface;
use AndrewDyer\JwtAuth\Contracts\ClaimsFactoryInterface;
use AndrewDyer\JwtAuth\Contracts\JwtProviderInterface;
use AndrewDyer\JwtAuth\Contracts\JwtSubjectInterface;
use AndrewDyer\JwtAuth\Exceptions\InvalidCredentialsException;
use AndrewDyer\JwtAuth\Exceptions\InvalidTokenException;

/**
 * Entry point for JWT-based authentication.
 *
 * Orchestrates credential validation, token issuance, and token verification
 * by delegating to the injected authentication, JWT, and claims-factory providers.
 */
final readonly class JwtAuth
{
    /**
     * @param AuthProviderInterface  $authProvider  Resolves authenticated subjects from credentials or identifiers.
     * @param JwtProviderInterface   $jwtProvider   Handles encoding and decoding of JWT strings.
     * @param ClaimsFactoryInterface $claimsFactory Constructs the claims payload for a given subject.
     */
    public function __construct(
        private AuthProviderInterface $authProvider,
        private JwtProviderInterface $jwtProvider,
        private ClaimsFactoryInterface $claimsFactory,
    ) {
    }

    /**
     * Validates the provided credentials and issues a signed JWT on success.
     *
     * @param string $username The username or email to authenticate with.
     * @param string $password The plain-text password to verify.
     *
     * @return string A signed JWT string for the authenticated subject.
     *
     * @throws InvalidCredentialsException If the credentials do not match any known subject.
     */
    public function attempt(string $username, string $password): string
    {
        $user = $this->authProvider->byCredentials($username, $password);

        if ($user === null) {
            throw new InvalidCredentialsException();
        }

        return $this->fromSubject($user);
    }

    /**
     * Parses the given token, resolves the subject it identifies, and returns it.
     *
     * @param string $token A signed JWT string to authenticate against.
     *
     * @return JwtSubjectInterface The authenticated subject identified by the token's subject claim.
     *
     * @throws InvalidTokenException If the token is invalid or no subject is found for the encoded identifier.
     */
    public function authenticate(string $token): JwtSubjectInterface
    {
        $claims = $this->parse($token);

        $user = $this->authProvider->byId($claims->sub);

        if ($user === null) {
            throw new InvalidTokenException();
        }

        return $user;
    }

    /**
     * Decodes a JWT string and returns a typed Claims object.
     *
     * @param string $token The raw JWT string to decode and validate.
     *
     * @return Claims The structured and validated claims extracted from the token.
     *
     * @throws InvalidTokenException If the token cannot be decoded or does not yield a valid payload.
     */
    public function parse(string $token): Claims
    {
        $decoded = $this->jwtProvider->decode($token);

        if (!is_array($decoded) && !is_object($decoded)) {
            throw new InvalidTokenException();
        }

        return Claims::fromArray((array)$decoded);
    }

    /**
     * Builds a claims payload for the given subject and encodes it as a signed JWT string.
     *
     * @param JwtSubjectInterface $subject The authenticated entity for whom the token is issued.
     *
     * @return string A signed JWT string encoding the subject's claims.
     */
    private function fromSubject(JwtSubjectInterface $subject): string
    {
        $claims = $this->claimsFactory->forSubject($subject);

        return $this->jwtProvider->encode($claims->toArray());
    }
}
