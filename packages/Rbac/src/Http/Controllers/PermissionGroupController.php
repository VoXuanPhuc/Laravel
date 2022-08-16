<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Services\Interfaces\PermissionGroupServiceInterface;

class PermissionGroupController extends Controller
{


    public function __construct( protected PermissionGroupServiceInterface $permissionGroupService )
    {
    }


    public function index() {

        return $this->permissionGroupService->listPermissionGroup();
    }

    public function listPermissionByGroup() {
        return $this->permissionGroupService->listPermissionByGroup();
    }
}
