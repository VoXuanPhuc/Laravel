<?php

namespace Encoda\Dependency\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Core\Traits\HasUID;
use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Resource\Models\Resource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 *
 */
class Dependency extends Model
{

    use MultiTenancyModel, HasUID;

    /**
     * @var string
     */
    protected $table = 'dependencies';

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
        'organization_id',
        'object_id',
        'object_type',
        'dependency_scenario_id'
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'object',
        'dependencyDetails'
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'type'
    ];



    /**
     * @return MorphToMany
     */
    public function activities(): MorphToMany
    {
        return $this->morphedByMany(Activity::class, 'dependable');
    }

    /**
     * @return MorphToMany
     */
    public function resources(): MorphToMany
    {
        return $this->morphedByMany(Resource::class, 'dependable', 'dependency_details');
    }

    /**
     * @return HasMany
     */
    public function dependencyDetails(): HasMany
    {
        return $this->hasMany(DependencyDetail::class);
    }

    /**
     * @return BelongsTo
     */
    public function dependencyScenario(): BelongsTo
    {
        return $this->belongsTo(DependencyScenario::class);
    }

    /**
     * Get the parent object model (resource or activity).
     */
    public function object(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return Attribute
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: static function($value, $attributes) {
                switch ($attributes['object_type']){
                    case Resource::class:
                        return DependencyObjectTypes::RESOURCE->value;
                    case Activity::class:
                        return DependencyObjectTypes::ACTIVITY->value;
                    default:
                        return null;
                }
            }
        );
    }

}
