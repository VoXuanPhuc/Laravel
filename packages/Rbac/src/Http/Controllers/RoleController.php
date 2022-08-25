<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;

use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;

class RoleController extends Controller
{
    /**
     * @var RoleServiceInterface
     */
    protected RoleServiceInterface $roleService;

    /**
     * @param RoleServiceInterface $roleService
     */
    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @return mixed
     */
    public function list()
    {
        return $this->roleService->listRoles();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid )
    {
        return $this->roleService->getRole( $uid );
    }

    /**
     * @param CreateRoleRequest $request
     * @return mixed
     */
    public function create(CreateRoleRequest $request)
    {
        return $this->roleService->createRole($request);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $uid
     * @return mixed
     */
    public function update(UpdateRoleRequest $request, $uid)
    {
        return $this->roleService->updateRole($request, $uid);
    }

    /**
     * @param $uid
     * @return bool
     */
    public function delete($uid)
    {
        return $this->roleService->deleteRole($uid);
    }
}
