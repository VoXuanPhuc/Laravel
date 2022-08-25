<?php

namespace Encoda\Rbac\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Rbac\Models\Permission;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Prettus\Validator\Exceptions\ValidatorException;

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
        return parent::all( $columns );
    }

    /**
     * @param $id
     * @param $columns
     * @return mixed
     * @throws BadRequestException
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find( $id, $columns );
    }

    /**
     * @param array $attributes
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        Event::dispatch( 'identity.permission.create.before' );

        $permission = parent::create( $attributes );

        Event::dispatch( 'identity.permission.create.after' );

        return $permission->refresh();
    }

    /**
     * @param array $attributes
     * @param $id
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch( 'identity.permission.update.before' );

        $permission = parent::update($attributes, $id);

        Event::dispatch( 'identity.permission.update.after' );

        return $permission;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch( 'identity.permission.delete.before' );

        $result =  parent::delete( $id );

        Event::dispatch( 'identity.permission.delete.after' );

        return $result;
    }


    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid($uid, $column = ['*'])
    {
        return $this->findOneByField('uid', $uid, $column );
    }
}
