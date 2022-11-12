<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\RemoteAccessContract;
use Encoda\Core\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecoveryTime extends Model implements RemoteAccessContract
{
    use SoftDeletes;

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
        'organization_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
