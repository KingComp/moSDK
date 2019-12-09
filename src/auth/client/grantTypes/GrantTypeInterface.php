<?php


namespace MyObject\auth\client\grantTypes;


interface GrantTypeInterface
{
    /**
     * @return mixed
     */
    public function getGrantTypeName();
    public function getOptions();
}
