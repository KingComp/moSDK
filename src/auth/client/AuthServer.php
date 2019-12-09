<?php


namespace MyObject\auth\client;


use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use MyObject\auth\client\grantTypes\GrantTypeInterface;
use yii\base\Configurable;

class AuthServer extends GenericProvider implements AuthServerInterface, Configurable
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    public function requestTokenFor(GrantTypeInterface $grantType, $options = []): AccessTokenInterface
    {
        return $this->getAccessToken(
            $grantType->getGrantTypeName(),
            array_merge(
                $grantType->getOptions(),
                $options
            )
        );
    }
}
