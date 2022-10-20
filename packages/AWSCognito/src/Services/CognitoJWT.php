<?php

namespace Encoda\AWSCognito\Services;

use Encoda\Auth\DTOs\AuthChallengeDTO;
use Encoda\Auth\DTOs\TokenDTO;
use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\AWSCognito\Entities\JwtToken;
use Encoda\AWSCognito\Exceptions\InvalidTokenStructureException;
use Encoda\AWSCognito\Exceptions\MissingTokenException;
use Exception;
use Illuminate\Http\Request;

class CognitoJWT
{

    protected Request $request;

    protected ?JwtToken $token = null;

    public CognitoJWTManager $manager;

    protected CognitoUserService $userService;

    public function __construct( CognitoJWTManager $manager )
    {
        $this->manager = $manager;
        $this->userService = app( CognitoUserService::class );
    }

    /**
     * @param false $getPayload
     * @return array|bool
     * @throws MissingTokenException
     */
    public function check(bool $getPayload = false ): bool|array
    {
        $payload = $this->checkOrFail();

        return $getPayload ? $payload : true;
    }

    /**
     * @return array
     * @throws MissingTokenException
     */
    public function checkOrFail(): array
    {
        return $this->getPayload();
    }

    /**
     * @return array
     * @throws MissingTokenException
     * @throws Exception
     */
    public function getPayload(): array
    {
        $this->getToken();

        return $this->manager->decode( $this->token->value() );

    }

    /**
     * @throws MissingTokenException
     * @throws InvalidTokenStructureException
     */
    public function getToken(): ?JwtToken
    {
        if( !$this->request ) {
            $this->request = request();
        }

        $jwt = $this->request->bearerToken();

        if( strlen( $jwt ) <=0 ) {
            throw new MissingTokenException();
        }

        if( $this->token  == null ) {
            $this->token = $this->manager->parseToken( $jwt );
        }

        return $this->token;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest( Request $request ): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @throws UserNotFoundException
     */
    public function fromCredentials($credentials ): TokenDTO|AuthChallengeDTO
    {
        return $this->userService->authenticate( $credentials['email'], $credentials['password'] );
    }
}
