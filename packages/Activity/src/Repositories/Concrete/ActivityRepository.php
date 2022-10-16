<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Encoda\Core\Exceptions\NotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

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
     * @param array $attributes
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        $activity = parent::create($attributes);

        return $activity->refresh();
    }

}
