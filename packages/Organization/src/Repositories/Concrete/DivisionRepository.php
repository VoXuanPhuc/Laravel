<?php

namespace Encoda\Organization\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Division;
use Encoda\Organization\Repositories\Interfaces\DivisionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class DivisionRepository extends Repository implements DivisionRepositoryInterface
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

    /**
     * @param $uid
     * @param string[] $column
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function findByUid($uid, $column = ['*'])
    {
        return $this->findOneByField('uid', $uid, $column );
    }
}
