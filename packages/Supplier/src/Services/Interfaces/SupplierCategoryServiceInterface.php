<?php

namespace Encoda\Supplier\Services\Interfaces;

use Encoda\Supplier\Http\Requests\Category\CreateSupplierCategoryRequest;
use Encoda\Supplier\Http\Requests\Category\UpdateSupplierCategoryRequest;

interface SupplierCategoryServiceInterface
{
    public function listSupplierCategory();

    public function getSupplierCategory(string $uid);

    public function createSupplierCategory(CreateSupplierCategoryRequest $request);

    public function updateSupplierCategory(UpdateSupplierCategoryRequest $request, string $uid);

    public function deleteSupplierCategory(string $uid);
}
