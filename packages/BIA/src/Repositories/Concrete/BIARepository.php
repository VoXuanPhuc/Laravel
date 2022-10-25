<?php

namespace Encoda\BIA\Repositories\Concrete;

use Encoda\BIA\Models\BIA;
use Encoda\BIA\Repositories\Interfaces\BIARepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class BIARepository extends Repository implements BIARepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return BIA::class;
    }
}
