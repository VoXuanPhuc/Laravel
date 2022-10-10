<?php

namespace Encoda\Dependency\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Dependency\Enums\DependencyObjectTypes;
use Encoda\Resource\Models\Resource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DependencyDetail extends Model
{

    protected $table = 'dependency_details';

    /**
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'id',
        'dependable_type',
        'dependable_id',
        'dependency_id'
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'dependable'
    ];

    protected $appends = [
        'type'
    ];

    /**
     * @return BelongsTo
     */
    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Dependency::class);
    }

    /**
     * @return MorphTo
     */
    public function dependable(): MorphTo
    {
        return $this->morphTo('dependable');
    }

    /**
     * @return Attribute
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                switch ($attributes['dependable_type']){
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
