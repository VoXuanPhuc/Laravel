<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Core\Exceptions\NotFoundException;
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
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getPermission($uid)
    {
        $permission = $this->permissionRepository->findByUid($uid);

        if( !$permission ) {
            throw new NotFoundException( __('rbac::app.permission.not_found' ));
        }

        return $permission;
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
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updatePermission(UpdatePermissionRequest $request, $uid)
    {
        $permission = $this->getPermission( $uid );
        return $this->permissionRepository->update($request->all(), $permission->id );
    }

    /**
     * @param $uid
     * @return bool|null
     * @throws NotFoundException
     */
    public function deletePermission($uid)
    {
        $permission = $this->getPermission( $uid );

        return $this->permissionRepository->delete($permission->id);
    }
}
