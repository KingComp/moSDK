<?php


namespace MyObject\auth\middleware\yii;


use MyObject\auth\client\AuthServerInterface;
use yii\base\Configurable;

class HttpRequestTokenRepositoryBuilder implements Configurable
{
    /**
     * @var AuthServerInterface
     */
    private $authServer;

    /**
     * RepositoryBuilder constructor.
     * @param AuthServerInterface $authServer
     */
    public function __construct(AuthServerInterface $authServer)
    {
        $this->authServer = $authServer;
    }

    public function getTokenRepository($request){
        return new HttpRequestTokenRepository($request, $this->authServer);
    }
}
