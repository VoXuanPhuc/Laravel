<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Dependency\DTOs\DependencyFactorDTO;
use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Services\Interfaces\DependencyFactorServiceInterface;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;

class DependencyFactorService implements DependencyFactorServiceInterface
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected ResourceRepositoryInterface  $resourceRepository,
        protected SupplierRepositoryInterface $supplierRepository,
    )
    {
    }


    /**
     * @return array
     */
    public function getDependencyFactors()
    {
        $activities = $this->activityRepository->all();
        $resources = $this->resourceRepository->all();
        $suppliers = $this->supplierRepository->all();

        return [
            new DependencyFactorDTO( DependableObjectTypeEnum::ACTIVITY->value, 'Activities', $activities ),
            new DependencyFactorDTO( DependableObjectTypeEnum::RESOURCE->value, 'Resources', $resources ),
            new DependencyFactorDTO( DependableObjectTypeEnum::SUPPLIER->value, 'Supplier', $suppliers ),
        ];
    }
}
