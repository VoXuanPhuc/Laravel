<?php

namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Http\Requests\ForgotPasswordRequest;
use Encoda\Auth\Http\Requests\ConfirmForgotPasswordRequest;
use Encoda\Auth\Interfaces\PasswordServiceInterface;

class PasswordController extends Controller
{

    public function __construct(
        protected PasswordServiceInterface $passwordService

    )
    {
    }

    public function forgotPassword( ForgotPasswordRequest $request ) {
        return $this->passwordService->sendResetLink( $request );
    }

    public function confirmForgotPassword(ConfirmForgotPasswordRequest $request ) {
        return $this->passwordService->confirmResetPassword( $request );
    }

}
