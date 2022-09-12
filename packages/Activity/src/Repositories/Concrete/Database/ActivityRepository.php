<?php

namespace Encoda\Activity\Repositories\Concrete\Database;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\BadRequestException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ActivityRepository extends Repository implements ActivityRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return Activity::class;
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
     * @throws RepositoryException
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
        Event::dispatch( 'identity.activity.create.before' );

        $activity = parent::create( $attributes );

        Event::dispatch( 'identity.activity.create.after' );

        return $activity;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch( 'identity.activity.update.before' );

        $activity = parent::update($attributes, $id);

        Event::dispatch( 'identity.activity.update.after' );

        return $activity;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch( 'identity.activity.delete.before' );

        $result =  parent::delete( $id );

        Event::dispatch( 'identity.activity.delete.after' );

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
