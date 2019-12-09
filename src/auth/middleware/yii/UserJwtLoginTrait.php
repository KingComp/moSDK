<?php


namespace MyObject\auth\middleware\yii;


use MyObject\auth\token\MoJwtToken;
use Yii;
use yii\web\IdentityInterface;

trait UserJwtLoginTrait
{
    public $identityClass;

    abstract function afterLogin($identity, $cookieBased, $duration);
    abstract function beforeLogin($identity, $cookieBased, $duration);
    abstract function switchIdentity($identity, $duration = 0);

    function loginWithJwt(MoJwtToken $moJwtToken){
        /* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::findIdentity($moJwtToken->getIdentityId());
        if ($identity && $this->beforeLogin($identity, false, 0)) {
                $this->switchIdentity($identity);
                $id = $identity->getId();
                $ip = \Yii::$app->getRequest()->getUserIP();
                Yii::info("User '$id' logged in from $ip via JWT.", __METHOD__);
                $this->afterLogin($identity, true, 0);
        }
    }
}
