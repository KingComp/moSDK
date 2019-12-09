<?php


namespace MyObject\auth\client;


use League\OAuth2\Client\Token\AccessTokenInterface;
use MyObject\auth\client\grantTypes\GrantTypeInterface;

interface AuthServerInterface
{
    public function requestTokenFor(GrantTypeInterface $grantType):AccessTokenInterface;
}
