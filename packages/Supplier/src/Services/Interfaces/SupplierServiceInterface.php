<?php

namespace Encoda\Supplier\Services\Interfaces;

use Encoda\Supplier\Http\Requests\Supplier\CreateSupplierRequest;
use Encoda\Supplier\Http\Requests\Supplier\UpdateSupplierRequest;

interface SupplierServiceInterface
{
    public function listSupplier( );
    public function getSupplier(  $uid );
    public function createSupplier( CreateSupplierRequest $request );
    public function updateSupplier( UpdateSupplierRequest $request, string $uid );
    public function deleteSupplier( string $uid );
    public function export( $category = null, $range = 'all' );
}
