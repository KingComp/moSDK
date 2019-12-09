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
     * @var HttpRequestTokenRepositoryBuilder
     */
    private $tokenRepositoryBuilder;

    private $verificator;
    /**
     * @var IdentityInterface
     */
    private $identityRepository;
    /**
     * @var UserRegistrableInterface|null
     */
    private $userRepository;

    /**
     * JWTAuth constructor.
     * @param array $config
     * @param HttpRequestTokenRepositoryBuilder $tokenRepositoryBuilder
     * @param JwtVerificator $verificator
     * @param IdentityInterface $identityRepository
     * @param UserRegistrableInterface|null $userRepository
     */
    public function __construct(
        $config,
        HttpRequestTokenRepositoryBuilder $tokenRepositoryBuilder,
        JwtVerificator $verificator,
        IdentityInterface $identityRepository,
        UserRegistrableInterface $userRepository = null
    ) {
        parent::__construct($config);
        $this->tokenRepositoryBuilder = $tokenRepositoryBuilder;
        $this->verificator = $verificator;
        $this->identityRepository = $identityRepository;
        $this->userRepository = $userRepository;
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
        $moJwtToken = $httpRequestTokenRepository->getFreshToken();
        if (!$this->isTokenValid($moJwtToken)) {
            throw new ForbiddenHttpException('Token verification Error');
        }
        if ($this->autoRegistration === true && $this->isTokenValid($moJwtToken)) {
            $this->userRepository->registerUser(
                new MoUser(
                    new MoUserId($moJwtToken->getIdentityId()),
                    $moJwtToken->getEmail(),
                    $moJwtToken->getCompany()
                )
            );
        }
        $user->loginWithJwt($moJwtToken);
    }

    private function isTokenValid(MoJwtToken $token)
    {
        return $this->verificator->verify($token);
    }
}
