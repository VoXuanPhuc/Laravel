<?php

namespace Encoda\Rbac\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Rbac\Models\Role;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class RoleRepository extends Repository implements RoleRepositoryInterface
{
    /**
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    public function all($columns = ['*'])
    {
        return parent::all( $columns );
    }

    /**
     * @param $id
     * @param $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find( $id, $columns );
    }

    /**
     * @param array $attributes
     * @return mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        Event::dispatch('identity.role.create.before');


        $role = parent::create( $attributes );

        Event::dispatch('identity.role.create.after');

        // Refresh to get the uid
        return $role->refresh();
    }

    /**
     * @param array $attributes
     * @param $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('identity.role.update.before');

        $role = parent::update( $attributes, $id );

        Event::dispatch('identity.role.update.after');

        return $role;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('identity.role.delete.before');
        $result = parent::delete( $id );
        Event::dispatch('identity.role.delete.after');

        return $result;
    }

    /**
     * @param $uid
     * @param string[] $columns
     * @return mixed
     */
    public function findByUid($uid, $columns = ['*'])
    {
        return parent::findOneByField( 'uid', $uid, $columns  );
    }

    /**
     * @param array $uids
     * @param string[] $columns
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function findByUids($uids = [], $columns = ['*'])
    {
        return $this->findWhereIn( 'uid', $uids, $columns );
    }
}
