<?php

namespace Encoda\Organization\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUnit extends Model
{

    use SoftDeletes, MultiTenancyModel;

    protected $table = 'business_units';

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'id',
        'division_id',
        'organization_id',
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'organization_id',
        'division_id',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division() {
        return $this->belongsTo( Division::class );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
