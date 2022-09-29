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
     * @param Organization $organization
     * @return mixed
     */
    public function listResourceOwner($organization)
    {
        return $organization->resourceOwners()->paginate( config('config.pagination_size') );
    }

    /**
     * @param $organization
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getResourceOwner($organization, $uid)
    {
        $owner = $this->ownerRepository->findByUid( $uid );

        if( !$owner ) {
            throw new NotFoundException('Resource owner not found' );
        }

        return $owner;
    }

    /**
     * @param CreateResourceOwnerRequest $request
     * @param $organization
     * @return mixed
     */
    public function createResourceOwner(CreateResourceOwnerRequest $request, $organization)
    {
        $request->merge(
            [
                'organization_id' => $organization->id
            ]
        );

        return $this->ownerRepository->create( $request->all() );
    }

    /**
     * @param UpdateResourceOwnerRequest $request
     * @param $organization
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateResourceOwner(UpdateResourceOwnerRequest $request, $organization, $uid)
    {
        $owner = $this->getResourceOwner( $organization, $uid );

        return $this->ownerRepository->update( $request->all(), $owner->id );
    }

    /**
     * @param $organization
     * @param $uid
     * @throws NotFoundException
     */
    public function deleteResourceOwner($organization, $uid)
    {
        $owner = $this->getResourceOwner( $organization, $uid );
        $this->ownerRepository->delete($owner->id);
    }

    /**
     * @param Organization $organization
     */
    public function listAllResourceOwner($organization)
    {
        return $organization->resourceOwners;
    }
}
