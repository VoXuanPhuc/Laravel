<?php

namespace Encoda\Supplier\Http\Controllers;

use Encoda\Supplier\Http\Requests\Category\CreateSupplierCategoryRequest;
use Encoda\Supplier\Http\Requests\Category\UpdateSupplierCategoryRequest;
use Encoda\Supplier\Services\Interfaces\SupplierCategoryServiceInterface;

class SupplierCategoryController extends Controller
{
    public function __construct(
        protected SupplierCategoryServiceInterface $supplierCategoryService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->supplierCategoryService->listSupplierCategory();
    }


    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {
        return $this->supplierCategoryService->getSupplierCategory( $uid  );
    }

    /**
     * @param CreateSupplierCategoryRequest $request
     * @return mixed
     */
    public function create( CreateSupplierCategoryRequest $request ): mixed
    {

        return $this->supplierCategoryService->createSupplierCategory( $request );
    }

    /**
     * @param UpdateSupplierCategoryRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateSupplierCategoryRequest $request, $uid ): mixed
    {
        return $this->supplierCategoryService->updateSupplierCategory( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ): mixed
    {

        return $this->supplierCategoryService->deleteSupplierCategory( $uid );
    }


}
