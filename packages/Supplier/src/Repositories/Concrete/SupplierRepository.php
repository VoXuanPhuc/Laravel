<?php

namespace Encoda\Supplier\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Supplier\Models\Supplier;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository extends Repository implements SupplierRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Supplier::class;
    }
}
