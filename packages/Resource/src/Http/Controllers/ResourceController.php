<?php

namespace Encoda\Resource\Http\Controllers;

use Encoda\Resource\Http\Requests\Resource\CreateResourceRequest;
use Encoda\Resource\Http\Requests\Resource\UpdateResourceRequest;
use Encoda\Resource\Services\Interfaces\ResourceServiceInterface;

class ResourceController extends Controller
{
    public function __construct(
        protected ResourceServiceInterface $resourceService,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {

        return $this->resourceService->listResource( $this->getTenant() );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {

        return $this->resourceService->getResource( $this->getTenant(), $uid  );
    }

    /**
     * @param CreateResourceRequest $request
     * @return mixed
     */
    public function create( CreateResourceRequest $request ): mixed
    {

        return $this->resourceService->createResource( $request, $this->getTenant() );
    }

    /**
     * @param UpdateResourceRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateResourceRequest $request, $uid ): mixed
    {
        return $this->resourceService->updateResource( $request, $this->getTenant(), $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ): mixed
    {

        return $this->resourceService->deleteResource( $this->getTenant(), $uid );
    }

    /**
     * @return mixed
     */
    public function download() {
        return $this->resourceService->export();
    }
}
