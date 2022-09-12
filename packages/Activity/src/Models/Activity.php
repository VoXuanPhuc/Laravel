<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ActivityContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model implements ActivityContract
{
    use SoftDeletes;
    
    protected $fillable = [
        'uid',
        'name',
        'description',
        'number_of_location',
    ];
    
    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return BelongsToMany
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class);
    }

    /**
     * @return BelongsToMany
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class);
    }

    /**
     * @return HasOne
     */
    public function iTSolution(): HasOne
    {
        return $this->hasOne(ITSolution::class);
    }

    /**
     * @return BelongsToMany
     */
    public function remoteAccesses(): BelongsToMany
    {
        return $this->belongsToMany(RemoteAccess::class);
    }

    /**
     * @return BelongsToMany
     */
    public function utilities(): BelongsToMany
    {
        return $this->belongsToMany(Utility::class);
    }

}
