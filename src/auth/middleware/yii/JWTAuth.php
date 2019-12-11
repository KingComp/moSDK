<?php


namespace MyObject\auth\middleware\yii;


use Lcobucci\JWT\Token;
use MyObject\auth\token\verificator\JwtVerificator;
use MyObject\auth\token\MoJwtToken;
use MyObject\common\user\dto\MoUser;
use MyObject\common\user\interfaces\UserRegistrableInterface;
use MyObject\common\user\values\MoUserId;
use yii\filters\auth\AuthMethod;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;

class JWTAuth extends AuthMethod
{
    public $autoRegistration = false;

    /**
     * login user with data from JWT if no user found
     * @var bool
     */
    public $loginIfNotExists = true;

    /**
     * @var HttpRequestTokenRepositoryBuilder
     */
    private $tokenRepositoryBuilder;

    private $verificator;

    /**
     * @var UserRegistrableInterface|null
     */
    private $userRepository;

    /**
     * JWTAuth constructor.
     * @param array $config
     * @param HttpRequestTokenRepositoryBuilder $tokenRepositoryBuilder
     * @param JwtVerificator $verificator
     * @param UserRegistrableInterface|null $userService
     */
    public function __construct(
        HttpRequestTokenRepositoryBuilder $tokenRepositoryBuilder,
        JwtVerificator $verificator,
        UserRegistrableInterface $userService = null,
        $config = []
    ) {
        parent::__construct($config);
        $this->tokenRepositoryBuilder = $tokenRepositoryBuilder;
        $this->verificator = $verificator;
        $this->userRepository = $userService;
    }

    public function authenticate($user, $request, $response)
    {
        if (!($user instanceof JwtLoginInterface)) {
            throw new \Error("WebUser should implements JwtLoginInterface");
        }
        return $this->JwtAuthenticate($user, $request, $response);
    }

    private function JwtAuthenticate(JwtLoginInterface $user, $request, $response)
    {
        $httpRequestTokenRepository = $this->tokenRepositoryBuilder->getTokenRepository($request);
        if (!$moJwtToken = $httpRequestTokenRepository->getFreshToken()) {
            return true;
        }
        if (!$this->isTokenValid($moJwtToken)) {
            throw new ForbiddenHttpException('Token verification Error');
        }
        if ($this->autoRegistration === true && $this->isTokenValid($moJwtToken)) {
            $this->userRepository->registerUser(
                new MoUser(
                    new MoUserId($moJwtToken->getIdentityId()),
                    $moJwtToken->getEmail(),
                    $moJwtToken->getCompanyId()
                )
            );
        }
        $user->loginWithJwt($moJwtToken, $this->loginIfNotExists);
    }

    private function isTokenValid(MoJwtToken $token)
    {
        return $this->verificator->verify($token);
    }
}
