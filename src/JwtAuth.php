<?php

namespace Anddye\JwtAuth;

use Anddye\JwtAuth\Contracts\JwtSubject;
use Anddye\JwtAuth\Providers\AuthProviderInterface;
use Anddye\JwtAuth\Providers\JwtProviderInterface;

/**
 * Class JwtAuth.
 */
final class JwtAuth
{
    /**
     * @var JwtSubject|null
     */
    protected $actor = null;

    /**
     * @var AuthProviderInterface
     */
    protected $authProvider;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * JwtAuth constructor.
     *
     * @param AuthProviderInterface $authProvider
     * @param JwtProviderInterface  $jwtProvider
     * @param ClaimsFactory         $claimsFactory
     */
    public function __construct(AuthProviderInterface $authProvider, JwtProviderInterface $jwtProvider, ClaimsFactory $claimsFactory)
    {
        $this->authProvider = $authProvider;
        $this->factory = new Factory($claimsFactory, $jwtProvider);
        $this->parser = new Parser($jwtProvider);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return string|null
     */
    public function attempt(string $username, string $password): ?string
    {
        if (!$user = $this->authProvider->byCredentials($username, $password)) {
            return null;
        }

        return $this->fromSubject($user);
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function authenticate(string $token): self
    {
        $this->actor = $this->authProvider->byId(
            $this->parser->decode($token)->sub
        );

        return $this;
    }

    /**
     * @return JwtSubject|null
     */
    public function getActor(): ?JwtSubject
    {
        return $this->actor;
    }

    /**
     * @param JwtSubject $subject
     *
     * @return string
     */
    protected function fromSubject(JwtSubject $subject): string
    {
        return $this->factory->encode($this->makePayload($subject));
    }

    /**
     * @param JwtSubject $subject
     *
     * @return array
     */
    protected function getClaimsForSubject(JwtSubject $subject): array
    {
        return [
            'sub' => $subject->getJwtIdentifier(),
        ];
    }

    /**
     * @param JwtSubject $subject
     *
     * @return array
     */
    protected function makePayload(JwtSubject $subject): array
    {
        return $this->factory->withClaims($this->getClaimsForSubject($subject))->make();
    }
}
