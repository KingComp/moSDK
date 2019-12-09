<?php


namespace MyObject\auth\middleware\yii;


use MyObject\auth\token\MoJwtToken;

interface JwtLoginInterface
{
    public function loginWithJwt(MoJwtToken $moJwtToken);
}
