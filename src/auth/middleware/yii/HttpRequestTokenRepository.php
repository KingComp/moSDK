<?php


namespace MyObject\auth\middleware\yii;


use Lcobucci\JWT\Parser;
use MyObject\auth\client\AuthServerInterface;
use MyObject\auth\client\grantTypes\RefreshTokenGrantType;
use MyObject\auth\token\MoJwtToken;
use yii\web\Request;

/**
 * Class HttpRequestTokenRepository
 * Репозиторий извлекающий токен из Http запроса
 * @package MyObject\auth\middleware\yii
 */
class HttpRequestTokenRepository implements TokenRepository
{
    const ACCESS_TOKEN_COOKIE_NAME = 'access_token';
    const REFRESH_TOKEN_COOKIE_NAME = 'refresh_token';

    const ACCESS_TOKEN_HTTP_HEADER = 'Authorization';
    const REFRESH_TOKEN_HTTP_HEADER = 'x-refresh-token';

    /** @var Request */
    private $request;
    /**
     * @var AuthServerInterface
     */
    private $authServer;
    /**
     * @var Parser
     */
    private $parser;

    /** @var MoJwtToken */
    private $token;

    /** @var string */
    private $refreshToken;

    /**
     * HttpRequestTokenRepository constructor.
     * @param Request $request
     * @param AuthServerInterface $authServer
     */
    public function __construct(Request $request, AuthServerInterface $authServer)
    {
        $this->request = $request;
        $this->authServer = $authServer;
        $this->parser = new Parser();
        if (
            !($token = $this->getAuthTokenFromRequest()) ||
            !($refreshToken = $this->getRefreshTokenFromRequest())
        ) {
            $this->token = null;
        }else{
            $this->token = new MoJwtToken(
                $token,
                $refreshToken
            );
        }
    }

    public function getToken(): ?MoJwtToken
    {
        $this->token;
    }

    public function getFreshToken(): ?MoJwtToken
    {
        if (
            !is_null($this->token) &&
            $this->token->isExpired()
        ) {
            $token = $this->refreshToken();
        }
        return $token;
    }


    private function getAuthTokenFromRequest()
    {
        $request = $this->request;
        if ($request->headers->has(self::ACCESS_TOKEN_HTTP_HEADER)) {
            $bearerToken = $request->headers->get(self::ACCESS_TOKEN_HTTP_HEADER);
            $token = $this->extractTokenFromBearerString($bearerToken);
            return $this->parser->parse($token);
        }
        if ($request->cookies->has(self::ACCESS_TOKEN_COOKIE_NAME)) {
            $bearerToken = $request->cookies->get(self::ACCESS_TOKEN_COOKIE_NAME)->value;
            $token = $this->extractTokenFromBearerString($bearerToken);
            return $this->parser->parse($token);
        }
        return null;
    }

    private function getRefreshToken()
    {
        if (!$this->refreshToken) {
            $this->refreshToken = $this->getRefreshTokenFromRequest();
        }
        return $this->refreshToken;
    }

    private function getRefreshTokenFromRequest()
    {
        $request = $this->request;
        if ($request->headers->has(self::REFRESH_TOKEN_HTTP_HEADER)) {
            return $request->headers->get(self::REFRESH_TOKEN_HTTP_HEADER);
        }
        if ($request->cookies->has(self::REFRESH_TOKEN_COOKIE_NAME)) {
            return $request->cookies->get(self::REFRESH_TOKEN_COOKIE_NAME);
        }
        return null;
    }

    private function refreshToken()
    {
        $refreshToken = $this->getRefreshToken();
        $tokenResponse = $this->authServer->requestTokenFor(new RefreshTokenGrantType($refreshToken));
        if (
            ($token = $this->parser->parse($tokenResponse->getToken())) &&
            ( $refreshToken = $tokenResponse->getRefreshToken())
        ) {
            $this->token = new MoJwtToken(
                $token,
                $refreshToken
            );
        }
        return $this->token;
    }

    private function extractTokenFromBearerString($string)
    {
        if (preg_match('/^Bearer\s+(.*?)$/', $string, $matches)) {
            return $matches[1];
        } else {
            throw new \Exception('Cant`t set Bearer token. Bad token string');
        }
    }

}
