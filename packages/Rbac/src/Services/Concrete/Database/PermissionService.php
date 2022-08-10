<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Rbac\Http\Requests\Permission\CreatePermissionRequest;
use Encoda\Rbac\Http\Requests\Permission\UpdatePermissionRequest;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Encoda\Rbac\Services\Interfaces\PermissionServiceInterface;

class PermissionService implements PermissionServiceInterface
{

    protected PermissionRepositoryInterface $permissionRepository;

    /**
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct( PermissionRepositoryInterface $permissionRepository )
    {
        $this->permissionRepository = $permissionRepository;
    }


    /**
     * @return mixed
     */
    public function listPermissions()
    {
        return $this->permissionRepository->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPermission($id)
    {
        return $this->permissionRepository->find($id);
    }

    /**
     * @param CreatePermissionRequest $request
     * @return mixed
     */
    public function createPermission(CreatePermissionRequest $request)
    {
        return $this->permissionRepository->create($request->all());
    }

    /**
     * @param UpdatePermissionRequest $request
     * @param $id
     * @return mixed
     */
    public function updatePermission(UpdatePermissionRequest $request, $id)
    {
        return $this->permissionRepository->update($request->all(), $id);
    }

    /**
     * @param $id
     * @return bool|null
     */
    public function deletePermission($id)
    {
        return $this->permissionRepository->delete($id);
    }
}
