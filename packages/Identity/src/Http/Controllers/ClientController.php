<?php

namespace Encoda\Identity\Http\Controllers;

use Encoda\Identity\Services\Interfaces\UserServiceInterface;

class ClientController extends Controller
{

    protected UserServiceInterface $userService;

    public function listUsers() {
        return $this->userService->listUsers();
    }
}
