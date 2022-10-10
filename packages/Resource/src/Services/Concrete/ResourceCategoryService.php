<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Resource\Http\Requests\Category\CreateResourceCategoryRequest;
use Encoda\Resource\Http\Requests\Category\UpdateResourceCategoryRequest;
use Encoda\Organization\Models\Organization;
use Encoda\Resource\Repositories\Interfaces\ResourceCategoryRepositoryInterface;
use Encoda\Resource\Services\Interfaces\ResourceCategoryServiceInterface;

class ResourceCategoryService implements ResourceCategoryServiceInterface
{

    public function __construct(
        protected ResourceCategoryRepositoryInterface $categoryRepository
    )
    {
    }

    /**
     * @return mixed
     */
    public function listResourceCategory(): mixed
    {
        return $this->categoryRepository->all();
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
