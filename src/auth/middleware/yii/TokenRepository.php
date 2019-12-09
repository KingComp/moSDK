<?php


namespace MyObject\auth\middleware\yii;


use MyObject\auth\token\MoJwtToken;

interface TokenRepository
{
    public function getToken(): MoJwtToken;

    public function getFreshToken(): MoJwtToken;
}
