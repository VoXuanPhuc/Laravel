<?php

namespace Encoda\Rbac\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Prettus\Validator\Exceptions\ValidatorException;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends Repository implements PermissionRepositoryInterface
{
    /**
     * @return string
     */
    public function model()
    {
        return Permission::class;
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
     * @throws BadRequestException
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getPermission($id);
    }

    /**
     * @param array $attributes
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch( 'identity.permission.create.before' );

        $permission = $this->model->create( $attributes );

        Event::dispatch( 'identity.permission.create.after' );

        return $permission;
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
        $this->getPermission($id);

        return parent::update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     * @throws BadRequestException
     */
    public function delete($id)
    {
        return $this->getPermission($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     * @throws BadRequestException
     */
    private function getPermission($id)
    {
        $permission = $this->model->find($id);

        if (empty($permission)) {
            throw new BadRequestException("Permission is not found");
        }

        return $permission;
    }

}
