<?php

namespace Encoda\Supplier\Http\Controllers;

use Encoda\Supplier\Http\Requests\Supplier\CreateSupplierRequest;
use Encoda\Supplier\Http\Requests\Supplier\UpdateSupplierRequest;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierServiceInterface $supplierService,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->supplierService->listSupplier();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {
        return $this->supplierService->getSupplier( $uid );
    }

    /**
     * @param CreateSupplierRequest $request
     *
     * @return mixed
     */
    public function create( CreateSupplierRequest $request ): mixed
    {

        return $this->supplierService->createSupplier( $request );
    }

    /**
     * @param UpdateSupplierRequest $request
     * @param string                $uid
     *
     * @return mixed
     */
    public function update( UpdateSupplierRequest $request, string $uid ): mixed
    {
        return $this->supplierService->updateSupplier( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ): mixed
    {

        return $this->supplierService->deleteSupplier(  $uid );
    }

    /**
     * @return mixed
     */
    public function download() {
        $response = $this->supplierService->export();

        return $response->deleteFileAfterSend(false);
    }
}
