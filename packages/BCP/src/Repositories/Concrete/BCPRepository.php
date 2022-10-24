<?php

namespace Encoda\BCP\Repositories\Concrete;

use Encoda\BCP\Models\BCP;
use Encoda\BCP\Repositories\Interfaces\BCPRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class BCPRepository extends Repository implements BCPRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return BCP::class;
    }
}
