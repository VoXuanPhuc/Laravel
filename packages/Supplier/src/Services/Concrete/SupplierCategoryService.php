<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Supplier\Http\Requests\Category\CreateSupplierCategoryRequest;
use Encoda\Supplier\Http\Requests\Category\UpdateSupplierCategoryRequest;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Encoda\Supplier\Services\Interfaces\SupplierCategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Validator\Exceptions\ValidatorException;


/**
 *
 */
class SupplierCategoryService implements SupplierCategoryServiceInterface
{
    /**
     * @param SupplierCategoryRepositoryInterface $supplierCategoryRepository
     */
    public function __construct(
        protected SupplierCategoryRepositoryInterface $supplierCategoryRepository
    )
    {
    }

    /**
     * @return Collection
     * @throws NotFoundException
     */
    public function listSupplierCategory()
    {
        return tenant()->supplierCategories()->get();
    }


    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getSupplierCategory(string $uid)
    {
        $category = $this->supplierCategoryRepository->query()
            ->hasUID($uid)
            ->get()->first();

        if (!$category) {
            throw new NotFoundException('Supplier Category not found');
        }

        return $category;
    }

    /**
     * @param CreateSupplierCategoryRequest $request
     *
     * @return void
     * @throws ValidatorException
     */
    public function createSupplierCategory(CreateSupplierCategoryRequest $request)
    {
        return $this->supplierCategoryRepository->create($request->all())?->refresh();

    }

    /**
     * @param UpdateSupplierCategoryRequest $request
     * @param string                        $uid
     *
     * @return mixed
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function updateSupplierCategory(UpdateSupplierCategoryRequest $request, string $uid)
    {
        $category = $this->getSupplierCategory($uid);

        return ($this->supplierCategoryRepository->update($request->all(), $category->id))?->refresh();
    }

    /**
     * @param string       $uid
     *
     * @return int
     * @throws NotFoundException
     */
    public function deleteSupplierCategory(string $uid)
    {
        $category = $this->getSupplierCategory($uid);

        return $this->supplierCategoryRepository->delete($category->id);
    }
}
