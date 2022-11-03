<?php

namespace Encoda\Auth\Interfaces;

use Encoda\Auth\Http\Requests\ConfirmForgotPasswordRequest;
use Encoda\Auth\Http\Requests\ForgotPasswordRequest;

interface PasswordServiceInterface
{

    public function sendResetLink( ForgotPasswordRequest $request );

    public function confirmResetPassword( ConfirmForgotPasswordRequest $request);
}
