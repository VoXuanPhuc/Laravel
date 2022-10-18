<?php

namespace Encoda\Organization\Models;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Models\Application;
use Encoda\Activity\Models\Equipment;
use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Models\Utility;
use Encoda\Dependency\Models\DependencyScenario;
use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Encoda\EDocs\Traits\InteractsWithDocument;
use Encoda\Resource\Models\Resource;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Resource\Models\ResourceOwner;
use Encoda\Supplier\Models\Supplier;
use Encoda\Supplier\Models\SupplierCategory;
use Encoda\MultiTenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $uid
 * @property string $code
 * @property boolean $landlord
 * @property string $domain
 * @property string $logo_path
 * @property boolean is_active
 */
class Organization extends Tenant
{

    use SoftDeletes, InteractsWithDocument;
    use EasyActionLogTrait;

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
        'domain',
        'address',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $appends = [
        'friendly_url',
        'landlord',
        'logo_url'
    ];

    // ====== Attributes ==== //

    /**
     * @return bool
     */
    public function getLandlordAttribute() {
        return 'ESCALATE' == trim($this->code)
            && trim($this->domain) == trim(config('config.app_domain'));
    }

    /**
     * @return string
     */
    public function getFriendlyUrlAttribute() {
        $friendlyUrl = str_replace( config('config.app_domain'), '', $this->domain );

        return trim( $friendlyUrl, '.' );
    }

    public function getLogoUrlAttribute()
    {
        return $this->getDocuments('logo')?->first()?->url;
    }



    /**
     * @return HasMany
     */
    public function settings() {
        return $this->hasMany( OrganizationSettings::class, 'organization_id' );
    }

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
    public function resourceCategories():   HasMany
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

    /**
     * @return HasMany
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * @return HasMany
     */
    public function supplierCategories(): HasMany
    {
        return $this->hasMany(SupplierCategory::class);
    }

    /**
     * @return HasMany
     */
    public function dependencyScenarios(): HasMany
    {
        return $this->hasMany(DependencyScenario::class);
    }


}
