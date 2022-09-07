<?php

namespace Encoda\Organization\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\BusinessUnit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class BusinessUnitRepository extends Repository
{

    public function model()
    {
        return BusinessUnit::class;
    }

    /**
     * @param array $attributes
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        $businessUnit = parent::create($attributes);

        return $businessUnit->refresh();
    }
}
