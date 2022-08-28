<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Services\Interfaces\UserRoleServiceInterface;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function __construct( protected UserRoleServiceInterface $userRoleService)
    {
    }

    public function assignRoleToUser( Request $request, $userUid ) {
        $this->userRoleService->assignUserRole( $userUid, $request );
    }

}
