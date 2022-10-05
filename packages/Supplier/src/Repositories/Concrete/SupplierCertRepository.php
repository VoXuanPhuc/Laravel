<?php

namespace Encoda\Supplier\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Supplier\Models\SupplierCert;
use Encoda\Supplier\Repositories\Interfaces\SupplierCertRepositoryInterface;

class SupplierCertRepository extends Repository implements SupplierCertRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return SupplierCert::class;
    }
}
