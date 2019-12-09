<?php


namespace MyObject\common\user\dto;


use MyObject\common\user\interfaces\MoUserInterface;
use MyObject\common\user\values\MoUserId;

/**
 * Class MoUser
 * DTO описывающий минимально необходимые данные необходимые для егистрации пользователя в любом сервисе MyObject
 *
 * @package MyObject\common\user\dto
 */
class MoUser implements MoUserInterface{
    private $id;
    private $email;
    private $companyId;

    public function __construct(MoUserId $id, $email, $company)
    {
        $this->id = (string) $id;
        $this->email = $email;
        $this->companyId = $company;
    }

    public function getId()
    {
        return $this->id;
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
