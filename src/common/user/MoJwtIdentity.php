<?php


namespace MyObject\common\user;


use MyObject\auth\token\MoJwtToken;
use MyObject\common\user\interfaces\MoUserInterface;
use yii\base\Exception;
use yii\web\IdentityInterface;

class MoJwtIdentity implements MoUserInterface, IdentityInterface
{
    /**
     * @var MoJwtToken
     */
    private $jwt;

    private $id;

    private $companyId;

    private $email;

    /**
     * JwtIdentity constructor.
     * @param $jwt
     */
    public function __construct(MoJwtToken $jwt)
    {
        $this->jwt = $jwt;
        $this->id =  $jwt->getIdentityId();
        $this->email = $jwt->getEmail();
        $this->companyId = $jwt->getCompanyId();
    }

    public static function findIdentity($id)
    {
        throw new Exception('Not Implemented');
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new Exception('Not Implemented');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        throw new Exception('Not Implemented');
    }

    public function validateAuthKey($authKey)
    {
        throw new Exception('Not Implemented');
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function getEmail()
    {
        return $this->email;
    }

}
