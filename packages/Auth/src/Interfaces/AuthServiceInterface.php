<?php

namespace Encoda\Auth\Interfaces;

use Encoda\Auth\Http\Requests\ChangePasswordRequest;

interface AuthServiceInterface
{

    public function getToken( $username, $password );

    public function authenticate(\Illuminate\Http\Request $request);

    public function respondForceChangePasswordChallenge(ChangePasswordRequest $request);
}
