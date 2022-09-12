<?php

namespace Encoda\Activity\Repositories\Concrete\Database;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\BadRequestException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redirect;
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
        return parent::all($columns);
    }
    
    /**
     * @param array $attributes
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws ValidatorException
     * @throws BadRequestException
     */
    public function create(array $attributes): mixed
    {
        $iTSolution = [];
        $iTSolution['data_type'] = $attributes['data_type'];
        $iTSolution['location'] = $attributes['storage'];
        
        DB::beginTransaction();
    
        try {
            Event::dispatch( 'identity.activity.create.before' );
    
            $activity = parent::create( $attributes );
            
            $activity->roles()->sync($attributes['role_ids']);
    
            $activity->utilities()->sync($attributes['utility_ids']);
    
            $activity->remoteAccesses()->sync($attributes['remote_access_ids']);

            $activity->applications()->sync($attributes['application_ids']);

            $activity->iTSolution()->create($iTSolution);

            $activity->devices()->sync($attributes['device_ids']);

            Event::dispatch( 'identity.activity.create.after' );
            
        } catch (Exception $e) {
            DB::rollback();
            
            throw new BadRequestException( 'Something went wrong');
        }
    
        DB::commit();
        
        return $activity;
    }
    
    /**
     * @param array $attributes
     * @param $uid
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws ValidatorException
     * @throws BadRequestException
     */
    public function update(array $attributes, $uid): mixed
    {
        $iTSolution = [];
        $iTSolution['data_type'] = $attributes['data_type'];
        $iTSolution['location'] = $attributes['storage'];
        
        $activity = $this->findByUid($uid);
    
        DB::beginTransaction();
    
        try {
            Event::dispatch( 'identity.activity.update.before' );
    
            $activity = parent::update($attributes, $activity->id);
    
            $activity->roles()->sync($attributes['role_ids']);
    
            $activity->utilities()->sync($attributes['utility_ids']);
    
            $activity->remoteAccesses()->sync($attributes['remote_access_ids']);
    
            $activity->applications()->sync($attributes['application_ids']);
    
            $activity->iTSolution()->update($iTSolution);
    
            $activity->devices()->sync($attributes['device_ids']);
    
            Event::dispatch( 'identity.activity.update.after' );
            
        } catch (Exception $e) {
            DB::rollback();
            
            throw new BadRequestException( 'Something went wrong');
        }
    
        DB::commit();
    
        return $activity->load(['roles', 'utilities', 'remoteAccesses', 'applications', 'iTSolution', 'devices']);
    }
    
    
    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     * @throws BadRequestException
     */
    public function findByUid($uid, $column = ['*']): mixed
    {
        $activity = $this->findOneByField('uid', $uid, $column);
    
        if (!$activity) {
            return throw new BadRequestException('Activity not found');
        }
    
        return $activity->load(['roles', 'utilities', 'remoteAccesses', 'applications', 'iTSolution', 'devices']);
    }
}
