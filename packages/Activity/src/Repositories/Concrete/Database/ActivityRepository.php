<?php

namespace Encoda\Activity\Repositories\Concrete\Database;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\NotFoundException;

class ActivityRepository extends Repository implements ActivityRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Activity::class;
    }
    
    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     * @throws NotFoundException
     */
    public function findByUid($uid, $column = ['*']): mixed
    {
        $activity = $this->findOneByField('uid', $uid, $column);
    
        if (!$activity) {
            throw new NotFoundException( __('activity::app.activity.not_found') );
        }
    
        return $activity;
    }
}
