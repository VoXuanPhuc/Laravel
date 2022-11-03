<?php

namespace Encoda\AWSCognito\Brokers;

use Closure;
use Encoda\AWSCognito\Services\CognitoPasswordService;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Support\Facades\Password;

class CognitoPasswordBroker implements PasswordBrokerContract
{

    public function __construct(
        protected CognitoPasswordService $passwordService

    )
    {
    }

    public function sendResetLink(array $credentials, Closure $callback = null)
    {
       $result = $this->passwordService->forgotPassword( $credentials );

       if ( isset($result['statusCode']) &&  $result['statusCode'] == 200){
           return Password::RESET_LINK_SENT;
       }

        return Password::RESET_THROTTLED;

    }

    public function reset(array $credentials, Closure $callback)
    {
        $result = $this->passwordService->confirmForgotPassword($credentials);

        if ( isset($result['statusCode']) &&  $result['statusCode'] == 200){
            return Password::PASSWORD_RESET;
        }

        return Password::INVALID_TOKEN;
    }
}
