<?php

namespace Encoda\Auth\Interfaces;

use Encoda\Auth\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;

interface AuthChallengeServiceInterface
{

    public function respondForceChangePasswordChallenge( ChangePasswordRequest $request );
}
