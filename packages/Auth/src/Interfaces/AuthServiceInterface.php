<?php

namespace Encoda\Auth\Interfaces;

interface AuthServiceInterface
{

    public function getToken( $username, $password );

    public function authenticate(\Illuminate\Http\Request $request);
}
