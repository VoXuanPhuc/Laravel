<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Services\Interfaces\DependableServiceInterface;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;

class DependableService implements DependableServiceInterface
{


    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected ResourceRepositoryInterface $resourceRepository,
        protected SupplierRepositoryInterface $supplierRepository,

    )
    {
    }

    /**
     * @param $type
     * @param $uids
     * @return mixed
     */
    public function getDependableByTypeAndUids($type, $uids ): mixed
    {

        return match ($type) {
            DependableObjectTypeEnum::ACTIVITY->value => $this->activityRepository->findByUids($uids, 'id'),
            DependableObjectTypeEnum::RESOURCE->value => $this->resourceRepository->findByUids($uids, 'id'),
            DependableObjectTypeEnum::SUPPLIER->value => $this->supplierRepository->findByUids($uids, 'id'),
            default => null,
        };

    }

    /**
     * @param $type
     * @param $uid
     * @return mixed
     */
    public function getDependableByTypeAndUid( $type, $uid ): mixed
    {

        return match ($type) {
            DependableObjectTypeEnum::ACTIVITY->value => $this->activityRepository->findByUid($uid, 'id'),
            DependableObjectTypeEnum::RESOURCE->value => $this->resourceRepository->findByUid($uid, 'id'),
            DependableObjectTypeEnum::SUPPLIER->value => $this->supplierRepository->findByUid($uid, 'id'),
            default => null,
        };

    }
}
