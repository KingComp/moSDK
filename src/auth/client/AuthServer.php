<?php


namespace MyObject\auth\client;


use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use MyObject\auth\client\grantTypes\GrantTypeInterface;

class AuthServer extends GenericProvider implements AuthServerInterface
{
    public function requestTokenFor(GrantTypeInterface $grantType, $options = []):AccessTokenInterface
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
