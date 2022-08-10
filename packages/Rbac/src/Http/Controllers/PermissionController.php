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
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->permissionService->getPermission($id);
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
     * @param $id
     * @return mixed
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        return $this->permissionService->updatePermission($request, $id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->permissionService->deletePermission($id);
    }
}
