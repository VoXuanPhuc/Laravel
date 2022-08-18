<?php

namespace Encoda\Rbac\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Rbac\Models\Role;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Error;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Prettus\Validator\Exceptions\ValidatorException;

class RoleRepository extends Repository implements RoleRepositoryInterface
{
    protected $model;

    protected PermissionRepositoryInterface $permissionRepository;

    /**
     * @param Application $app
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        Application $app,
        PermissionRepositoryInterface $permissionRepository
    )
    {
        $this->permissionRepository = $permissionRepository;
        parent::__construct($app);
    }

    /**
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    /**
     * @param $columns
     * @return Collection|mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @param $id
     * @param $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getRole($id);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('identity.role.create.before');

        $role = $this->model->create( $attributes );

        Event::dispatch('identity.role.create.after');

        return $role;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws BadRequestException
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        $this->getRole($id);

        return parent::update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     * @throws BadRequestException
     */
    public function delete($id)
    {
        return $this->getRole($id)->delete();
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     * @throws BadRequestException
     */
    public function createRolePermission(array $attributes, $id)
    {
        $role = $this->getRole($id);
        $permission = $this->getPermission($attributes['permission_id']);

        return $role->givePermissionTo($permission);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return bool
     * @throws BadRequestException
     */
    public function checkRolePermission(array $attributes,  $id)
    {
        $role = $this->getRole($id);
        $permission = $this->getPermission($attributes['permission_id']);

        if (!$role->hasPermissionTo($permission)) {
            throw new BadRequestException("This role doesn't has this permission");
        }

        return true;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     * @throws BadRequestException
     */
    public function removeRolePermission(array $attributes, $id)
    {
        $role = $this->getRole($id);
        $permission = $this->getPermission($attributes['permission_id']);

        //check role has permission
        if (!$role->hasPermissionTo($permission)) {
            throw new BadRequestException("This role doesn't has this permission");
        }

        return $role->revokePermissionTo($permission);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     * @throws BadRequestException
     */
    public function updateRolePermission(array $attributes, $id)
    {
        $role = $this->getRole($id);
        $permission = $this->getPermission($attributes['permission_id']);

        return $role->syncPermissions($permission);
    }

    /**
     * @param $roleId
     * @return mixed
     * @throws BadRequestException
     */
    private function getRole($roleId)
    {
        $role = $this->findOneByField('uid', $roleId);

        if (empty($role)) {
            return throw new BadRequestException('Role is not found');
        }

        return $role;
    }

    /**
     * @param $permissionId
     * @return mixed
     * @throws BadRequestException
     */
    private function getPermission($permissionId)
    {
        $permission = $this->permissionRepository->find($permissionId);

        if (empty($permission)) {
            throw new BadRequestException("Permission is not found");
        }

        return $permission;
    }
}
