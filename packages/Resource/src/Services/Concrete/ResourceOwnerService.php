<?php

namespace Encoda\Resource\Services\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Resource\Http\Requests\Owner\CreateResourceOwnerRequest;
use Encoda\Resource\Http\Requests\Owner\UpdateResourceOwnerRequest;
use Encoda\Organization\Models\Organization;
use Encoda\Resource\Models\ResourceOwner;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;
use Illuminate\Validation\ValidationException;

class ResourceOwnerService implements \Encoda\Resource\Services\Interfaces\ResourceOwnerServiceInterface
{

    public function __construct(
        protected ResourceOwnerRepositoryInterface $ownerRepository
    )
    {
    }

    /**
     * @return mixed
     * @throws ValidationException
     */
    public function listResourceOwner()
    {
        $query = $this->ownerRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->ownerRepository->applyPaging($query);
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
            ->setTable(ResourceOwner::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['first_name', 'last_name', 'is_invite', 'email'])
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
            ->setTable(ResourceOwner::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['first_name', 'last_name', 'is_invite', 'email'])
            ->validate()
            ->applySort();
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
