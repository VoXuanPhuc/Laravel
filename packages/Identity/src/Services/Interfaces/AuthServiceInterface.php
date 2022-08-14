<?php

namespace Encoda\Identity\Services\Interfaces;

interface AuthServiceInterface
{

    public function getToken($username, $password);
}
