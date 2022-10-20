<?php

namespace Encoda\Dependency\Traits;

use Encoda\Activity\Models\Activity;
use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Dependency\Models\Dependency;
use Encoda\Resource\Models\Resource;
use Encoda\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 */
trait DependencyModelTrait
{


    /**
     * @return string
     */
    public function getTypeAttribute() {

        return match (get_class($this)) {
            Activity::class => DependableObjectTypeEnum::ACTIVITY->value,
            Resource::class => DependableObjectTypeEnum::RESOURCE->value,
            Supplier::class => DependableObjectTypeEnum::SUPPLIER->value,
            default => '',
        };
    }

    /**
     * @return string
     */
    public function getTagColorAttribute() {

        return match (get_class($this)) {
            Activity::class => 'bg-c1-800',
            Resource::class => 'bg-cWarning-600',
            Supplier::class => 'bg-cSuccess-600',
            default => 'bg-c1-800',
        };
    }

    /**
     * @return Dependency
     */
    public function toDependency( $workflow = 'Target' ) {

        /** @var Dependency $dependency */
        $dependency = app(Dependency::class );

        $dependency->dependable_id = $this->id;
        $dependency->workflow = $workflow;
        $dependency->dependable()->associate( $this );

        return $dependency;
    }

    /**
     * @return MorphMany
     */
    public function dependencies(): MorphMany
    {
        return $this->morphMany( Dependency::class, 'dependable' );
    }
}
