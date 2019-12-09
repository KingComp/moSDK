<?php


namespace MyObject\common\user\interfaces;


use yii\web\IdentityInterface;

interface UserRegistrableInterface
{
    public function registerUser(MoUserInterface $user);
}
