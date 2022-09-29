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
     * @param Organization $organization
     * @return mixed
     */
    public function listResourceCategory($organization)
    {
        return $organization->resourceCategories;
    }

    /**
     * @param Organization $organization
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getResourceCategory($organization, $uid)
    {
        $category = $this->categoryRepository->findByUid( $uid );

        if( !$category ) {
            throw new NotFoundException('Resource Category not found');
        }

        return $category;
    }

    public function createResourceCategory(CreateResourceCategoryRequest $request, $organization)
    {
        $request->merge(
            [
                'organization_id' => $organization->id,
            ]
        );

        return $this->categoryRepository->create( $request->all() );

    }


    /**
     * @param UpdateResourceCategoryRequest $request
     * @param $organization
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateResourceCategory(UpdateResourceCategoryRequest $request, $organization, $uid)
    {
        $category = $this->getResourceCategory( $organization, $uid );

        return $this->categoryRepository->update( $request->all(), $category->id );
    }

    /**
     * @param $organization
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteResourceCategory($organization, $uid)
    {
        $category = $this->getResourceCategory( $organization, $uid );

        return $this->categoryRepository->delete( $category->id );
    }
}
