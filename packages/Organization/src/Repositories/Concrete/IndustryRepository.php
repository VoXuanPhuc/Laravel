<?php

namespace Encoda\Organization\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Repositories\Interfaces\IndustryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IndustryRepository extends Repository implements IndustryRepositoryInterface
{

    public function model()
    {
        return Industry::class;
    }
}
