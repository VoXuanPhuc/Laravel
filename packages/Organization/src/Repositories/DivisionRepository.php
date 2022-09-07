<?php

namespace Encoda\Organization\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Division;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class DivisionRepository extends Repository
{

    public function model()
    {
        return Division::class;
    }

    /**
     * @param array $attributes
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $attributes)
    {
        $division = parent::create($attributes);

        return $division->refresh();
    }
}
