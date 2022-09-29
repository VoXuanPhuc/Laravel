<?php

namespace Encoda\Resource\Http\Controllers;

use Encoda\Resource\Http\Requests\Owner\CreateResourceOwnerRequest;
use Encoda\Resource\Http\Requests\Owner\UpdateResourceOwnerRequest;
use Encoda\Resource\Services\Interfaces\ResourceOwnerServiceInterface;

class ResourceOwnerController extends Controller
{

    public function __construct(
        protected ResourceOwnerServiceInterface $resourceOwnerService,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {

        return $this->resourceOwnerService->listResourceOwner( $this->getTenant() );
    }

    /**
     * @return mixed
     */
    public function all(): mixed
    {

        return $this->resourceOwnerService->listAllResourceOwner( $this->getTenant() );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {

        return $this->resourceOwnerService->getResourceOwner( $this->getTenant(), $uid  );
    }

    /**
     * @param CreateResourceOwnerRequest $request
     * @return mixed
     */
    public function create( CreateResourceOwnerRequest $request ): mixed
    {

        return $this->resourceOwnerService->createResourceOwner( $request, $this->getTenant() );
    }

    /**
     * @param UpdateResourceOwnerRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateResourceOwnerRequest $request, $uid ): mixed
    {
        return $this->resourceOwnerService->updateResourceOwner( $request, $this->getTenant(), $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ): mixed
    {

        return $this->resourceOwnerService->deleteResourceOwner( $this->getTenant(), $uid );
    }

}
