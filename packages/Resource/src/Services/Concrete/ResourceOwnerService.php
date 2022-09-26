<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Resource\Http\Requests\Owner\CreateResourceOwnerRequest;
use Encoda\Resource\Http\Requests\Owner\UpdateResourceOwnerRequest;
use Encoda\Organization\Models\Organization;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;

class ResourceOwnerService implements \Encoda\Resource\Services\Interfaces\ResourceOwnerServiceInterface
{

    public function __construct(
        protected ResourceOwnerRepositoryInterface $ownerRepository
    )
    {
    }

    /**
     * @return mixed
     */
    public function listResourceOwner()
    {
        return $this->ownerRepository->paginate( config('config.pagination_size') );
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getResourceOwner( $uid )
    {
        $owner = $this->ownerRepository->findByUid( $uid );

        if( !$owner ) {
            throw new NotFoundException('Resource owner not found' );
        }

        return $owner;
    }

    /**
     * @param CreateResourceOwnerRequest $request
     * @return mixed
     */
    public function createResourceOwner( CreateResourceOwnerRequest $request )
    {

        return $this->ownerRepository->create( $request->all() );
    }

    /**
     * @param UpdateResourceOwnerRequest $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateResourceOwner(UpdateResourceOwnerRequest $request, $uid)
    {
        $owner = $this->getResourceOwner( $uid );

        return $this->ownerRepository->update( $request->all(), $owner->id );
    }

    /**
     * @param $uid
     * @throws NotFoundException
     */
    public function deleteResourceOwner( $uid )
    {
        $owner = $this->getResourceOwner( $uid );
        $this->ownerRepository->delete($owner->id);
    }

    /**
     * List all resource owner, no pagination
     * @return mixed
     */
    public function listAllResourceOwner()
    {
        return $this->ownerRepository->all();
    }
}
