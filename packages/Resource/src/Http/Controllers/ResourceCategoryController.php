<?php

namespace Encoda\Resource\Http\Controllers;

use Encoda\Resource\Http\Requests\Category\CreateResourceCategoryRequest;
use Encoda\Resource\Http\Requests\Category\UpdateResourceCategoryRequest;
use Encoda\Resource\Services\Interfaces\ResourceCategoryServiceInterface;

class ResourceCategoryController extends Controller
{

    public function __construct(
        protected ResourceCategoryServiceInterface $resourceCategoryService,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {

        return $this->resourceCategoryService->listResourceCategory( $this->getTenant() );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {

        return $this->resourceCategoryService->getResourceCategory( $this->getTenant(), $uid  );
    }

    /**
     * @param CreateResourceCategoryRequest $request
     * @return mixed
     */
    public function create( CreateResourceCategoryRequest $request ): mixed
    {

        return $this->resourceCategoryService->createResourceCategory( $request, $this->getTenant() );
    }

    /**
     * @param UpdateResourceCategoryRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateResourceCategoryRequest $request, $uid ): mixed
    {
        return $this->resourceCategoryService->updateResourceCategory( $request, $this->getTenant(), $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ): mixed
    {

        return $this->resourceCategoryService->deleteResourceCategory( $this->getTenant(), $uid );
    }
}
