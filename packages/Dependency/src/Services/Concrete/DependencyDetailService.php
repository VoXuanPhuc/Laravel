<?php

namespace Encoda\Dependency\Services\Concrete;

use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Dependency\Models\Dependency;
use Encoda\Dependency\Models\DependencyDetail;
use Encoda\Dependency\Repositories\Interfaces\DependencyDetailRepositoryInterface;
use Encoda\Dependency\Services\Interfaces\DependencyDetailServiceInterface;
use Encoda\Resource\Services\Interfaces\ResourceServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class DependencyDetailService implements DependencyDetailServiceInterface
{
    public function __construct(
        protected DependencyDetailRepositoryInterface $dependencyDetailRepository,
        protected ActivityServiceInterface           $activityService,
        protected ResourceServiceInterface           $resourceService,
    )
    {
    }

    /**
     * @param Dependency $dependency
     * @param array      $data
     *
     * @return DependencyDetail|null
     */
    public function createDependencyDetail(Dependency $dependency, array $data): ?DependencyDetail
    {
        $dependencyObject = $this
            ->getDependencyObject(DependencyObjectTypes::from($data['type']), $data['uid']);
        $dependencyDetail = $this->dependencyDetailRepository->newInstance([
            'type' => Arr::get($data, 'dependent_type')
        ]);
        /**
         * @var DependencyDetail $dependencyDetail
         */
        $dependencyDetail->dependency()->associate($dependency);
        $dependencyDetail->dependable()->associate($dependencyObject);
        $dependencyDetail->save();
        return $dependencyDetail->refresh();
    }

    /**
     * @param DependencyObjectTypes $type
     * @param string                $uid
     *
     * @return void
     * @throws NotFoundException
     */
    public function getDependencyObject(DependencyObjectTypes $type, string $uid)
    {
        switch ($type) {
            case DependencyObjectTypes::RESOURCE:
                return $this->resourceService->getResource($uid);
            case DependencyObjectTypes::ACTIVITY:
                return $this->activityService->getActivity($uid);
        }
    }
}
