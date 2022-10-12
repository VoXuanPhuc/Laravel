<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Supplier\Http\Requests\Category\CreateSupplierCategoryRequest;
use Encoda\Supplier\Http\Requests\Category\UpdateSupplierCategoryRequest;
use Encoda\Supplier\Models\SupplierCategory;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Encoda\Supplier\Services\Interfaces\SupplierCategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
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
        $query = tenant()->supplierCategories();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->supplierCategoryRepository->applyPaging($query, false);
    }

    /**
     * @param $query
     *
     * @throws ValidationException
     */
    public function applySearchFilter($query)
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(SupplierCategory::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description'])
            ->validate()
            ->applyFilter();
        return $query;
    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySortFilter($query): void
    {
        //Apply sort
        SortFluent::init()
            ->setTable(SupplierCategory::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
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
