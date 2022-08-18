<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Http\Controllers\Controller;
use Encoda\Rbac\Http\Requests\Permission\CreatePermissionRequest;
use Encoda\Rbac\Http\Requests\Permission\UpdatePermissionRequest;
use Encoda\Rbac\Services\Interfaces\PermissionServiceInterface;

class PermissionController extends Controller
{
    /**
     * @var PermissionServiceInterface
     */
    protected PermissionServiceInterface $permissionService;

    /**
     * @param PermissionServiceInterface $permissionService
     */
    public function __construct( PermissionServiceInterface $permissionService )
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @return mixed
     */
    public function list()
    {
        return $this->permissionService->listPermissions();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail($uid)
    {
        return $this->permissionService->getPermission($uid);
    }

    /**
     * @param CreatePermissionRequest $request
     * @return mixed
     */
    public function create(CreatePermissionRequest $request)
    {
        return $this->permissionService->createPermission($request);
    }

    /**
     * @param UpdatePermissionRequest $request
     * @param $uid
     * @return mixed
     */
    public function update(UpdatePermissionRequest $request, $uid)
    {
        return $this->permissionService->updatePermission($request, $uid);
    }

    /**
     * @param $uid
     * @return bool
     */
    public function delete($uid)
    {
        return $this->permissionService->deletePermission($uid);
    }
}
