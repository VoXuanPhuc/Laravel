<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Services\Interfaces\UserRoleServiceInterface;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function __construct( protected UserRoleServiceInterface $userRoleService)
    {
    }

    /**
     * @param Request $request
     * @param $userUid
     */
    public function assignRoleToUser( Request $request, $userUid ) {
        return $this->userRoleService->assignUserRole( $request, $userUid  );
    }

}
