<?php


namespace MyObject\auth\client\grantTypes;


class RefreshTokenGrantType implements GrantTypeInterface
{

    protected $refreshToken;

    /**
     * TokenGrantType constructor.
     * @param $refreshToken
     */
    public function __construct($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }


    public function getGrantTypeName()
    {
        return 'refreshToken';
    }

    public function getOptions()
    {
       return [
           'refresh_token' => $this->refreshToken
       ];
    }
}
