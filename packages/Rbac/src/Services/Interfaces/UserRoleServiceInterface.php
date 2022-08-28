<?php

namespace Encoda\Rbac\Services\Interfaces;

use Laravel\Lumen\Http\Request;

interface UserRoleServiceInterface
{

    public function assignUserRole( Request $request, $userUid );
}
