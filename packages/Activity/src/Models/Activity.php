<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ActivityContract;
use Encoda\Organization\Models\BusinessUnit;
use Encoda\Organization\Models\Division;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model implements ActivityContract
{
    use SoftDeletes;

    const CREATED = 1;
    const IN_PROGRESS = 2;
    const FINISHED = 3;

    const STEP_ACTIVITY_INFO = 1; //Activity info
    const STEP_ACTIVITY_REMOTE_ACCESS_FACTOR = 2; //Activity remote access factor
    const STEP_ACTIVITY_SOFTWARE_EQUIPMENT = 3; //Software and equipment

    protected $table = 'activities';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'description',
        'is_remote',
        'status',
        'step',
        'on_site_requires',
        'division_id',
        'min_people',
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

    protected $casts = [
        'is_remote' => 'boolean'
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
    public function alternativeRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'activity_alternative_role', 'activity_id', 'alternative_role_id');
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
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class);
    }

    /**
     * @return HasOne
     */
    public function itSolution(): HasOne
    {
        return $this->hasOne(ITSolution::class);
    }

    /**
     * @return BelongsToMany
     */
    public function remoteAccessFactors(): BelongsToMany
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
    public function division() : BelongsTo {
        return $this->belongsTo( Division::class );
    }

    /**
     * @return BelongsTo
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

}
