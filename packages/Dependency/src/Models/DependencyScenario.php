<?php

namespace Encoda\Dependency\Models;

use Encoda\Core\Traits\HasUID;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
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

    /**
     * @return HasMany
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(Dependency::class);
    }

}
