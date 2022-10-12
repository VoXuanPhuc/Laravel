<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Resource\Http\Requests\Category\CreateResourceCategoryRequest;
use Encoda\Resource\Http\Requests\Category\UpdateResourceCategoryRequest;
use Encoda\Organization\Models\Organization;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Resource\Repositories\Interfaces\ResourceCategoryRepositoryInterface;
use Encoda\Resource\Services\Interfaces\ResourceCategoryServiceInterface;
use Illuminate\Validation\ValidationException;

class ResourceCategoryService implements ResourceCategoryServiceInterface
{

    public function __construct(
        protected ResourceCategoryRepositoryInterface $categoryRepository
    )
    {
    }

    /**
     * @return mixed
     * @throws ValidationException
     */
    public function listResourceCategory(): mixed
    {
        $query = $this->categoryRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->categoryRepository->applyPaging($query, false);
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
            ->setTable(ResourceCategory::getTableName())
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
            ->setTable(ResourceCategory::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
    }


    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getResourceCategory( $uid ): mixed
    {
        $category = $this->categoryRepository->findByUid( $uid );

        if( !$category ) {
            throw new NotFoundException('Resource Category not found');
        }

        return $category;
    }

    /**
     * @param CreateResourceCategoryRequest $request
     * @return mixed
     */
    public function createResourceCategory( CreateResourceCategoryRequest $request ): mixed
    {
        return $this->categoryRepository->create( $request->all() );

    }


    /**
     * @param UpdateResourceCategoryRequest $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateResourceCategory(UpdateResourceCategoryRequest $request, $uid): mixed
    {
        $category = $this->getResourceCategory( $uid );

        return $this->categoryRepository->update( $request->all(), $category->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteResourceCategory( $uid )
    {
        $category = $this->getResourceCategory( $uid );

        return $this->categoryRepository->delete( $category->id );
    }
}
