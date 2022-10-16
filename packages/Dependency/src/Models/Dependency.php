<?php

namespace Encoda\Dependency\Models;

use Encoda\Core\Traits\HasUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property object $dependable
 * @property string $dependable_type
 * @property int $dependable_id
 * @property string $workflow
 */
class Dependency extends Model
{

    use HasUID;

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
        'dependable_type',
        'dependable_id',
        'workflow',
        'dependency_scenario_id'
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'dependable',
    ];

    /**
     * @return BelongsTo
     */
    public function dependencyScenario(): BelongsTo
    {
        return $this->belongsTo(DependencyScenario::class);
    }

    /**
     * Get the parent object model (resource or activity).
     * @return MorphTo
     */
    public function dependable(): MorphTo
    {
        return $this->morphTo();
    }

}
