<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ActivityContract;
use Encoda\Activity\Pivots\ActivityDisruptionScenario;
use Encoda\Activity\Pivots\ActivityRecoveryTime;
use Encoda\Core\Models\Model;
use Encoda\Dependency\Models\DependencyScenario;
use Encoda\Dependency\Traits\DependencyModelTrait;
use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Encoda\Identity\Models\Database\User;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;
use Encoda\Notification\Traits\NotifySender;
use Encoda\Organization\Models\BusinessUnit;
use Encoda\Organization\Models\Division;
use Encoda\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $uid
 * @property $name
 * @property $description
 * @property $min_people
 * @property $is_remote
 * @property $on_site_requires
 * @property $status
 * @property $step
 */
class Activity extends Model implements ActivityContract
{
    use SoftDeletes, MultiTenancyModel, DependencyModelTrait;
    use EasyActionLogTrait;
    use NotifySender;

    const CREATED = 1;
    const IN_PROGRESS = 2;
    const FINISHED = 3;

    const STEP_ACTIVITY_INFO = 1; //Activity info
    const STEP_ACTIVITY_REMOTE_ACCESS_FACTOR = 2; //Activity remote access factor
    const STEP_ACTIVITY_SOFTWARE_EQUIPMENT = 3; //Software and equipment
    const STEP_DEPENDENCY_SUPPLIER = 4; //Dependency and supplier

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
        'organization_id',
    ];

    protected $casts = [
        'is_remote' => 'boolean'
    ];

    protected $appends = [
        'type',
        'tag_color',
    ];


    /**
     * @return BelongsTo
     */
    public function assignee() {
        return $this->belongsTo( User::class, 'assignee_id' );
    }

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

    /**
     * @return BelongsToMany
     */
    public function tolerablePeriodDisruptions(): BelongsToMany
    {
        return $this->belongsToMany(TolerableTimePeriod::class)
            ->withPivot(["dependent_time", "reason_choose_dependent_time"])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function recoveryTimes(): BelongsToMany
    {
        return $this->belongsToMany(RecoveryTime::class)
            ->using(ActivityRecoveryTime::class)
            ->withPivot(["is_rto_tested"])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function disruptionScenarios(): BelongsToMany
    {
        return $this->belongsToMany(DisruptionScenario::class)
            ->using(ActivityDisruptionScenario::class)
            ->withPivot(["workaround_solution", "workaround_feasibly"])
            ->withTimestamps();
    }
    /**
     * @return BelongsToMany
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }

    /**
     * @return BelongsToMany
     */
    public function dependencyScenarios(): BelongsToMany
    {
        return $this->belongsToMany(DependencyScenario::class);
    }
}
