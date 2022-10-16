<?php

namespace Encoda\Dependency\Models;

use Encoda\Core\Traits\HasUID;
use Encoda\Dependency\Enums\DependableTypeEnum;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property Collection $dependencies
 */
class DependencyScenario extends Model
{

    use MultiTenancyModel, HasUID;

    /**
     * @var string
     */
    protected $table = 'dependency_scenarios';

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
        'uid',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'id',
        'organization_id'
    ];

    protected $appends = [
        'targetCount',
        'upstreamCount',
        'downstreamCount',
    ];

    /**
     * @return HasMany
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(Dependency::class );
    }

    /**
     * @return Collection
     */
    public function allDependencies(): Collection
    {
        return $this->dependencies->map( function( $item ) {
            /** @var Dependency $item */
            return $item->dependable;
        });
    }


    /**
     * @return Collection
     */
    public function targetDependencies(): Collection
    {
        return $this->getWorkflowDependencies( DependableTypeEnum::TARGET->value );
    }



    /**
     * @return Collection
     */
    public function upstreamDependencies(): Collection
    {
        return $this->getWorkflowDependencies( DependableTypeEnum::UPSTREAM->value );
    }

    /**
     * @return Collection
     */
    public function downstreamDependencies(): Collection
    {
        return $this->getWorkflowDependencies( DependableTypeEnum::DOWNSTREAM->value );
    }


    /**
     * @param string $workflow
     * @return Collection|\Illuminate\Support\Collection
     */
    protected function getWorkflowDependencies(string $workflow = 'Target' )
    {

        return $this->dependencies()
            ->where('workflow', '=', $workflow )
            ->get()
            ->map( function( $item ) {
                /** @var Dependency $item */
                return $item->dependable;
            });
    }

    /**
     * @return $this
     */
    public function loadDependencies(): static
    {

        $this->attributes['targets'] = $this->targetDependencies();
        $this->attributes['upstream'] = $this->upstreamDependencies();
        $this->attributes['downstream'] = $this->downstreamDependencies();

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetCountAttribute(): int
    {
        return $this->targetDependencies()->count();
    }


    /**
     * @return int
     */
    public function getUpstreamCountAttribute(): int
    {
        return $this->upstreamDependencies()->count();
    }


    /**
     * @return int
     */
    public function getDownstreamCountAttribute(): int
    {
        return $this->downstreamDependencies()->count();
    }
}
