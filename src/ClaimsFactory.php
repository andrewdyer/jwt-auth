<?php

namespace Anddye\JwtAuth;

/**
 * Class ClaimsFactory.
 */
final class ClaimsFactory
{
    /**
     * @var int
     */
    protected $exp;

    /**
     * @var int
     */
    protected $iat;

    /**
     * @var string
     */
    protected $iss;

    /**
     * @var string
     */
    protected $jti;

    /**
     * @var int
     */
    protected $nbf;

    /**
     * @param array $claims
     *
     * @return ClaimsFactory
     */
    public static function build(array $claims): ClaimsFactory
    {
        $claimsFactory = new ClaimsFactory();

        if (isset($claims['exp'])) {
            $claimsFactory->setExp($claims['exp']);
        }

        if (isset($claims['iat'])) {
            $claimsFactory->setIat($claims['iat']);
        }

        if (isset($claims['iss'])) {
            $claimsFactory->setIss($claims['iss']);
        }

        if (isset($claims['jti'])) {
            $claimsFactory->setJti($claims['jti']);
        }

        if (isset($claims['nbf'])) {
            $claimsFactory->setNbf($claims['nbf']);
        }

        return $claimsFactory;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @return int
     */
    public function getIat(): int
    {
        return $this->iat;
    }

    /**
     * @return string
     */
    public function getIss(): string
    {
        return $this->iss;
    }

    /**
     * @return string
     */
    public function getJti(): string
    {
        return $this->jti;
    }

    /**
     * @return int
     */
    public function getNbf(): int
    {
        return $this->nbf;
    }

    /**
     * @param int $exp
     *
     * @return $this
     */
    public function setExp(int $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * @param int $iat
     *
     * @return $this
     */
    public function setIat(int $iat): self
    {
        $this->iat = $iat;

        return $this;
    }

    /**
     * @param string $iss
     *
     * @return $this
     */
    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    /**
     * @param string $jti
     *
     * @return $this
     */
    public function setJti(string $jti): self
    {
        $this->jti = $jti;

        return $this;
    }

    /**
     * @param int $nbf
     *
     * @return $this
     */
    public function setNbf(int $nbf): self
    {
        $this->nbf = $nbf;

        return $this;
    }
}
