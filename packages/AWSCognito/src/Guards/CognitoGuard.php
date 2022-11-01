<?php

namespace Encoda\AWSCognito\Guards;

use Encoda\Auth\DTOs\AuthChallengeDTO;
use Encoda\Auth\DTOs\TokenDTO;
use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\AWSCognito\Exceptions\InvalidTokenStructureException;
use Encoda\AWSCognito\Exceptions\MissingTokenException;
use Encoda\AWSCognito\Factories\CognitoAuthFactory;
use Encoda\AWSCognito\Services\CognitoJWT;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Macroable;
use Laravel\Lumen\Application;

class CognitoGuard implements Guard
{

    use GuardHelpers;

    /**
     * The user we last attempted to retrieve.
     *
     * @var Authenticatable
     */
    protected Authenticatable $lastAttempted;

    protected Request $request;

    /** @var CognitoJWT  */
    protected CognitoJWT $jwt;

    /**
     * @param CognitoJWT $jwt
     * @param UserProvider $provider
     * @param Request $request
     */
    public function __construct( CognitoJWT $jwt, UserProvider $provider, Request $request )
    {

        $this->jwt = $jwt;
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * @return Authenticatable|void|null
     * @throws MissingTokenException|InvalidTokenStructureException
     */
    public function user()
    {
        if( $this->user != null) {
            return $this->user;
        }

        if(
            $this->jwt->setRequest( $this->request )->getToken()
            && $payload = $this->jwt->check( true )
        ) {
            return $this->user = $this->provider->retrieveById( $payload['sub'] );
        }
    }

    /**
     * @param array $credentials
     * @return bool
     * @throws UserNotFoundException
     */
    public function validate(array $credentials = []): bool
    {
        return (bool) $this->attempt( $credentials, false );
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param UserProvider $provider
     * @return $this
     */
    public function setProvider( UserProvider $provider ): static
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return UserProvider
     */
    public function getProvider(): UserProvider
    {
        return $this->provider;
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param array $credentials
     * @param bool $login
     * @return mixed
     * @throws UserNotFoundException
     */
    public function attempt(array $credentials = [], bool $login = true)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $this->login( $credentials ) : true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials(mixed $user, array $credentials = [] ): bool
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Return the currently cached user.
     *
     * @return Authenticatable|null
     */
    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    /**
     * Get the current request instance.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request ?: Request::createFromGlobals();
    }


    /**
     * @param $jwt
     */
    public function setJwt( $jwt ) {
        $this->jwt = $jwt;
    }

    /**
     * @return mixed
     */
    public function getJwt(): mixed
    {
        return $this->jwt;
    }


    /**
     * @param $credentials
     * @return AuthChallengeDTO|TokenDTO
     * @throws UserNotFoundException
     */
    protected function login( $credentials )
    {
        return $this->jwt->fromCredentials( $credentials );
    }

    /**
     * @return CognitoAuthFactory
     */
    public function factory(): CognitoAuthFactory
    {
        return $this->jwt->manager->getAuthFactory();
    }

}
