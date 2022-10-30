<?php

namespace Encoda\EasyLog\Models;

use Carbon\Carbon;
use Encoda\EasyLog\Enums\LogEventEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Encoda\EasyLog\Contracts\EasyLog as EasyLogContract;

/**
 * Encoda\EasyLog\Models\Activity.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property Collection|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $causer
 * @property-read Collection $changes
 * @property-read Model|Eloquent $subject
 *
 * @method static Builder|EasyLog causedBy(Model $causer)
 * @method static Builder|EasyLog forBatch(string $batchUuid)
 * @method static Builder|EasyLog forEvent(string $event)
 * @method static Builder|EasyLog forSubject(Model $subject)
 * @method static Builder|EasyLog hasBatch()
 * @method static Builder|EasyLog inLog($logNames)
 * @method static Builder|EasyLog newModelQuery()
 * @method static Builder|EasyLog newQuery()
 * @method static Builder|EasyLog query()
 */
class EasyLog extends \Encoda\Core\Models\Model implements EasyLogContract
{

    use EasyLogChangesTrait, EasyLogCauserTrait, EasyLogDateTrait;

    public $guarded = [];

    protected $hidden = [
        'id',
        'properties',
        'subject_id',
        'causer',
        'causer_id',
        'causer_type',
        'batch_uuid',
//        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'properties' => 'collection',
        'event' => LogEventEnum::class,
    ];


    protected $appends = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('easylog.database_connection'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('easylog.table_name'));
        }

        parent::__construct($attributes);
    }

    /**
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        if (config('easylog.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }

        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param string $propertyName
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->properties->toArray(), $propertyName, $defaultValue);
    }

    /**
     * @return Collection
     */
    public function changes(): Collection
    {
        if (! $this->properties instanceof Collection) {
            return new Collection();
        }

        return $this->properties->only(['attributes', 'old']);
    }

    /**
     * @return Collection
     */
    public function getChangesAttribute(): Collection
    {
        return $this->changes();
    }

    /**
     * @param Builder $query
     * @param ...$logNames
     * @return Builder
     */
    public function scopeInLog(Builder $query, ...$logNames): Builder
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    /**
     * @param Builder $query
     * @param Model $causer
     * @return Builder
     */
    public function scopeCausedBy(Builder $query, Model $causer): Builder
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }

    /**
     * @param Builder $query
     * @param Model $subject
     * @return Builder
     */
    public function scopeForSubject(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * @param Builder $query
     * @param string $event
     * @return Builder
     */
    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasBatch(Builder $query): Builder
    {
        return $query->whereNotNull('batch_uuid');
    }

    /**
     * @param Builder $query
     * @param string $batchUuid
     * @return Builder
     */
    public function scopeForBatch(Builder $query, string $batchUuid): Builder
    {
        return $query->where('batch_uuid', $batchUuid);
    }
}
