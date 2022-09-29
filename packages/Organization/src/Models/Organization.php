<?php

namespace Encoda\Organization\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Models\Application;
use Encoda\Activity\Models\Equipment;
use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Models\Utility;
use Encoda\Resource\Models\Resource;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Resource\Models\ResourceOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property string $uid
 */
class Organization extends Model
{

    use SoftDeletes;

    protected $table = 'organizations';

    protected $hidden  = [
        'id',
        'owner_id',
    ];

    protected $guarded = [
        'id',
        'owner_id',
    ];

    protected $fillable = [
        'name',
        'code',
        'description',
        'logo_path',
        'friendly_url',
        'custom_domain',
        'address',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];


    /***
     * @return HasMany
     */
    public function divisions() {
        return $this->hasMany( Division::class, 'organization_id' );
    }

    /**
     * @return HasOne
     */
    public function owner() {
        return $this->hasOne( OrganizationOwner::class );
    }

    public function industries() {
        return $this->belongsToMany( Industry::class, 'organization_with_industries' );
    }

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

    /**
     * @return HasMany
     */
    public function applications() {
        return $this->hasMany( Application::class );
    }

    /**
     * @return HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany( Equipment::class );
    }

    /**
     * @return HasMany
     */
    public function utilities(): HasMany
    {
        return $this->hasMany( Utility::class );
    }

    /**
     * @return HasMany
     */
    public function remoteAccessFactors(): HasMany
    {
        return $this->hasMany( RemoteAccessFactor::class );
    }

    // ========== RESOURCES =======//

    /**
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany( Resource::class );
    }
    /**
     * @return HasMany
     */
    public function resourceCategories(): HasMany
    {
        return $this->hasMany( ResourceCategory::class );
    }

    /**
     * @return HasMany
     */
    public function resourceOwners(): HasMany
    {
        return $this->hasMany( ResourceOwner::class );
    }
}
