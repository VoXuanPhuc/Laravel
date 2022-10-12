<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\UtilityContract;
use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Utility extends Model implements UtilityContract
{
    use SoftDeletes, MultiTenancyModel;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'uid',
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];

    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
