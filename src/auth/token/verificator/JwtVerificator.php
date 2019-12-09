<?php

namespace MyObject\auth\token\verificator;


use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use MyObject\auth\token\MoJwtToken;

class JwtVerificator
{
    /** @var string */
    protected $key;
    /** @var Signer|null */
    protected $signer;

    /**
     * JwtVerificator constructor.
     * @param string $key
     * @param Signer|null $signer
     */
    public function __construct(string $key, Signer $signer = null)
    {
        if ($signer === null) {
            $this->signer = $this->getDefaultSigner();
        } else {
            $this->signer = $signer;
        }
        if (is_readable($key)) {
            $this->key = file_get_contents($key);
        } else {
            $this->key = $key;
        }
    }

    private function getDefaultSigner()
    {
        return new Sha256();
    }

    public function verify(MoJwtToken $token)
    {
        return $token->getJwt()->verify($this->signer, $this->key);
    }
}
