<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\Http\Requests\ConfirmForgotPasswordRequest;
use Encoda\Auth\Http\Requests\ForgotPasswordRequest;
use Encoda\Auth\Interfaces\PasswordServiceInterface;
use Encoda\AWSCognito\Services\CognitoPasswordService;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordService implements PasswordServiceInterface
{

    public function __construct(
        protected PasswordBroker $passwordBroker,
        protected CognitoPasswordService $passwordService

    )
    {
    }

    public function sendResetLink( ForgotPasswordRequest $request )
    {
        return $this->passwordBroker->sendResetLink(
            $request->toArray()
        );
    }

    public function confirmResetPassword(ConfirmForgotPasswordRequest $request)
    {
        return $this->passwordBroker->reset( $request->toArray(), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );
    }
}
