<?php


namespace MyObject\auth\client\grantTypes;


class PasswordGrantType implements GrantTypeInterface
{
    private $password;
    private $username;

    /**
     * PasswordGrant constructor.
     * @param $password
     * @param $username
     */
    public function __construct($password, $username)
    {
        $this->password = $password;
        $this->username = $username;
    }


    public function getGrantTypeName()
    {
        return 'password';
    }

    public function getOptions()
    {
        return [
            'password' => $this->password,
            'username' => $this->username
        ];
    }


}
