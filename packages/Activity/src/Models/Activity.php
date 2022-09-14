<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ActivityContract;
use Encoda\Organization\Models\BusinessUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model implements ActivityContract
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'is_remoted',
        'number_of_location',
        'division_id',
        'business_unit_id',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'division_id',
        'business_unit_id',
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
    public function remoteAccessesFactors(): BelongsToMany
    {
        return $this->belongsToMany(RemoteAccessFactor::class);
    }

    /**
     * @return BelongsToMany
     */
    public function utilities(): BelongsToMany
    {
        return $this->belongsToMany(Utility::class);
    }
    
    /**
     * @return BelongsTo
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

}
