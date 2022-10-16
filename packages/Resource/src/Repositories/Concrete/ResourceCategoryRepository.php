<?php

namespace Encoda\Resource\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Resource\Repositories\Interfaces\ResourceCategoryRepositoryInterface;

class ResourceCategoryRepository extends Repository implements ResourceCategoryRepositoryInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return ResourceCategory::class;
    }

}
