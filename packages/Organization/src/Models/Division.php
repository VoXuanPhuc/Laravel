<?php

namespace Encoda\Organization\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Notification\Traits\NotifySender;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes, MultiTenancyModel;
    use NotifySender;

    protected $table = 'divisions';

    protected $guarded = [
        'id',

    ];

    protected $fillable = [
        'name',
        'description',
        'avatar_color',
        'organization_id',
        'is_active',
    ];

    protected $hidden  = [
        'id',
        'organization_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    /**
     * @return HasMany
     */
    public function business_units() {
        return $this->hasMany( BusinessUnit::class );
    }

    /**
     * Get all the activities for the organization.
     * @return HasManyThrough
     */
    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(Activity::class, BusinessUnit::class);
    }
}
