<?php


namespace MyObject\auth\token;


use Lcobucci\JWT\Token;

/**
 * Class MoJwtToken
 *  JWT token специфичны для сервисов MyObject
 * @package MyObject\auth\token
 */
class MoJwtToken
{
    private $token;
    /**
     * @var string
     */
    private $refreshToken;

    /**
     * MoJwtToken constructor.
     * @param Token $jwtToken
     * @param string $refreshToken
     */
    public function __construct(Token $jwtToken, string $refreshToken)
    {
        $this->token = $jwtToken;
        $this->refreshToken = $refreshToken;
    }

    public function getIdentityId()
    {
        return $this->getClaim('sub');
    }

    public function getEmail()
    {
        return $this->getClaim('email');
    }

    public function getCompanyId()
    {
        return $this->getClaim('companyid');
    }

    public function getJwt()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function isExpired()
    {
        return $this->getJwt()->isExpired();
    }
}
