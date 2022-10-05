<?php

namespace Encoda\Supplier\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Supplier\Models\SupplierCategory;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;

class SupplierCategoryRepository extends Repository implements SupplierCategoryRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return SupplierCategory::class;
    }
}
