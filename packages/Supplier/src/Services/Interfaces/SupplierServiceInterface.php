<?php

namespace Encoda\Supplier\Services\Interfaces;

use Encoda\Supplier\Http\Requests\Supplier\CreateSupplierRequest;
use Encoda\Supplier\Http\Requests\Supplier\UpdateSupplierRequest;
use Encoda\Supplier\Models\Supplier;

interface SupplierServiceInterface
{
    public function listSupplier( );

    /**
     * @param $uid
     *
     * @return Supplier
     */
    public function getSupplier(  $uid );

    public function createSupplier( CreateSupplierRequest $request );
    public function updateSupplier( UpdateSupplierRequest $request, string $uid );
    public function deleteSupplier( string $uid );
    public function export( $category = null, $range = 'all' );
}
