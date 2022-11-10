<?php

namespace Encoda\EasyLog\Traits;

use Carbon\CarbonInterval;
use DateInterval;
use Encoda\EasyLog\Exceptions\InvalidConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Encoda\EasyLog\EasyLogger;
use Encoda\EasyLog\Providers\EasyLogServiceProvider;
use Encoda\EasyLog\Entities\EasyLogStatus;
use Encoda\EasyLog\Contracts\LoggablePipe;
use Encoda\EasyLog\Entities\EventLogBag;
use Encoda\EasyLog\Entities\LogOptions;

trait EasyActionLogTrait
{
    public static array $changesPipes = [];

    protected array $oldAttributes = [];

    protected ?LogOptions $easyLogOptions;

    public bool $enableLoggingModelsEvents = true;


    /**
     * @return LogOptions
     */
    public function getEasyLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ;
    }


    /**
     * Boot easy log
     */
    protected static function bootEasyActionLogTrait(): void
    {
        // Hook into eloquent events that only specified in $eventToBeRecorded array,
        // checking for "updated" event hook explicitly to temporary hold original
        // attributes on the model as we'll need them later to compare against.

        static::eventsToBeRecorded()->each(function ($eventName) {

            if ($eventName === 'updated') {
                static::updating(function (Model $model) {

                    $oldValues = (new static())->setRawAttributes($model->getRawOriginal());
                    $model->oldAttributes = static::logChanges($oldValues);
                });
            }

            static::$eventName(function (Model $model) use ($eventName) {

                $model->easyLogOptions = $model->getEasyLogOptions();

                if (! $model->shouldLogEvent($eventName)) {
                    return;
                }

                $changes = $model->attributeValuesToBeLogged($eventName);

                $description = $model->getDescriptionForEvent($eventName);

                $logName = $model->getLogNameToUse();

                // Submitting empty description will cause place holder replacer to fail.
                if ($description == '') {
                    return;
                }

                if ($model->isLogEmpty($changes) && ! $model->easyLogOptions->submitEmptyLogs) {
                    return;
                }

                // User can define a custom pipelines to mutate, add or remove from changes
                // each pipe receives the event carrier bag with changes and the model in
                // question every pipe should manipulate new and old attributes.
                $event = app(Pipeline::class)
                ->send(new EventLogBag($eventName, $model, $changes, $model->easyLogOptions))
                ->through(static::$changesPipes)
                ->thenReturn();

                // Actual logging
                $logger = app(EasyLogger::class)
                    ->useLog($logName)
                    ->event($eventName)
                    ->performedOn($model)
                    ->withProperties($event->changes);

                if (method_exists($model, 'tapEasyLog')) {
                    $logger->tap([$model, 'tapEasyLog'], $eventName);
                }

                $logger->log($description);

                // Reset log options so the model can be serialized.
                $model->easyLogOptions = null;
            });
        });
    }

    /**
     * @param LoggablePipe $pipe
     */
    public static function addLogChange(LoggablePipe $pipe): void
    {
        static::$changesPipes[] = $pipe;
    }

    /**
     * @param array $changes
     * @return bool
     */
    public function isLogEmpty(array $changes): bool
    {
        return empty($changes['attributes'] ?? []) && empty($changes['old'] ?? []);
    }

    /**
     * @return $this
     */
    public function disableLogging(): self
    {
        $this->enableLoggingModelsEvents = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function enableLogging(): self
    {
        $this->enableLoggingModelsEvents = true;

        return $this;
    }

    /**
     * @return MorphMany
     * @throws InvalidConfiguration
     */
    public function actions(): MorphMany
    {
        return $this->morphMany(EasyLogServiceProvider::determineEasyLogModel(), 'subject');
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if (! empty($this->easyLogOptions->descriptionForEvent)) {
            return ($this->easyLogOptions->descriptionForEvent)($eventName);
        }

        return $eventName;
    }

    /**
     * @return string|null
     */
    public function getLogNameToUse(): ?string
    {
        if (! empty($this->easyLogOptions->logName)) {
            return $this->easyLogOptions->logName;
        }

        return config('easylog.default_log_name');
    }

    /**
     * Get the event names that should be recorded.
     **/
    protected static function eventsToBeRecorded(): Collection
    {
        if (isset(static::$recordEvents)) {
            return collect(static::$recordEvents);
        }

        $events = collect([
            'created',
            'updated',
          //  'saved',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }

        return $events;
    }

    /**
     * @param string $eventName
     * @return bool
     */
    protected function shouldLogEvent(string $eventName): bool
    {
        $logStatus = app(EasyLogStatus::class);

        if (! $this->enableLoggingModelsEvents || $logStatus->disabled()) {
            return false;
        }

        if (! in_array($eventName, ['created', 'updated'])) {
            return true;
        }

        // Do not log update event if the model is restoring
        if ($this->isRestoring()) {
            return false;
        }

        // Do not log update event if only ignored attributes are changed.
        return (bool) count(Arr::except($this->getDirty(), $this->easyLogOptions->dontLogIfAttributesChangedOnly));
    }

    /**
     * Determines if the model is restoring.
     **/
    protected function isRestoring(): bool
    {
        $deletedAtColumn = method_exists($this, 'getDeletedAtColumn')
            ? $this->getDeletedAtColumn()
            : 'deleted_at';

        return $this->isDirty($deletedAtColumn) && count($this->getDirty()) === 1;
    }

    /**
     * Determines what attributes needs to be logged based on the configuration.
     **/
    public function attributesToBeLogged(): array
    {
        $this->easyLogOptions = $this->getEasyLogOptions();

        $attributes = [];

        // Check if fillable attributes will be logged then merge it to the local attributes array.
        if ($this->easyLogOptions->logFillable) {
            $attributes = array_merge($attributes, $this->getFillable());
        }

        // Determine if unguarded attributes will be logged.
        if ($this->shouldLogUnguarded()) {

            // Get only attribute names, not intrested in the values here then guarded
            // attributes. get only keys than not present in guarded array, because
            // we are logging the unguarded attributes and we cant have both!

            $attributes = array_merge($attributes, array_diff(array_keys($this->getAttributes()), $this->getGuarded()));
        }

        if (! empty($this->easyLogOptions->logAttributes)) {

            // Filter * from the logAttributes because will deal with it separately
            $attributes = array_merge($attributes, array_diff($this->easyLogOptions->logAttributes, ['*']));

            // If there's * get all attributes then merge it, dont respect $guarded or $fillable.
            if (in_array('*', $this->easyLogOptions->logAttributes)) {
                $attributes = array_merge($attributes, array_keys($this->getAttributes()));
            }
        }

        if ($this->easyLogOptions->logExceptAttributes) {

            // Filter out the attributes defined in ignoredAttributes out of the local array
            $attributes = array_diff($attributes, $this->easyLogOptions->logExceptAttributes);
        }

        return $attributes;
    }

    /**
     * @return bool
     */
    public function shouldLogUnguarded(): bool
    {
        if (! $this->easyLogOptions->logUnguarded) {
            return false;
        }

        // This case means all of the attributes are guarded
        // so we'll not have any unguarded anyway.
        if (in_array('*', $this->getGuarded())) {
            return false;
        }

        return true;
    }

    /**
     * Determines values that will be logged based on the difference.
     **/
    public function attributeValuesToBeLogged(string $processingEvent): array
    {
        // no loggable attributes, no values to be logged!
        if (! count($this->attributesToBeLogged())) {
            return [];
        }

        $properties['attributes'] = static::logChanges(

            // if the current event is retrieved, get the model itself
            // else get the fresh default properties from database
            // as wouldn't be part of the saved model instance.
            $processingEvent == 'retrieved'
                ? $this
                : (
                    $this->exists ? $this->fresh() ?? $this : $this
                )
        );

        if (static::eventsToBeRecorded()->contains('updated') && $processingEvent == 'updated') {

            // Fill the attributes with null values.
            $nullProperties = array_fill_keys(array_keys($properties['attributes']), null);

            // Populate the old key with keys from database and from old attributes.
            $properties['old'] = array_merge($nullProperties, $this->oldAttributes);

            // Fail safe.
            $this->oldAttributes = [];
        }

        if ($this->easyLogOptions->logOnlyDirty && isset($properties['old'])) {

            // Get difference between the old and new attributes.
            $properties['attributes'] = array_udiff_assoc(
                $properties['attributes'],
                $properties['old'],
                function ($new, $old) {
                    // Strict check for php's weird behaviors
                    if ($old === null || $new === null) {
                        return $new === $old ? 0 : 1;
                    }

                    // Handles Date interval comparisons since php cannot use spaceship
                    // Operator to compare them and will throw ErrorException.
                    if ($old instanceof DateInterval) {
                        return CarbonInterval::make($old)->equalTo($new) ? 0 : 1;
                    } elseif ($new instanceof DateInterval) {
                        return CarbonInterval::make($new)->equalTo($old) ? 0 : 1;
                    }

                    return $new <=> $old;
                }
            );

            $properties['old'] = collect($properties['old'])
                ->only(array_keys($properties['attributes']))
                ->all();
        }

        if (static::eventsToBeRecorded()->contains('deleted') && $processingEvent == 'deleted') {
            $properties['old'] = $properties['attributes'];
            unset($properties['attributes']);
        }

        return $properties;
    }

    /**
     * @param Model $model
     * @return array
     */
    public static function logChanges(Model $model): array
    {
        $changes = [];
        $attributes = $model->attributesToBeLogged();

        foreach ($attributes as $attribute) {
            if (Str::contains($attribute, '.')) {
                $changes += self::getRelatedModelAttributeValue($model, $attribute);

                continue;
            }

            if (Str::contains($attribute, '->')) {
                Arr::set(
                    $changes,
                    str_replace('->', '.', $attribute),
                    static::getModelAttributeJsonValue($model, $attribute)
                );

                continue;
            }

            $changes[$attribute] = in_array($attribute, $model->easyLogOptions->attributeRawValues)
                ? $model->getAttributeFromArray($attribute)
                : $model->getAttribute($attribute);

            if (is_null($changes[$attribute])) {
                continue;
            }

            if ($model->isDateAttribute($attribute)) {
                $changes[$attribute] = $model->serializeDate(
                    $model->asDateTime($changes[$attribute])
                );
            }

            if ($model->hasCast($attribute)) {
                $cast = $model->getCasts()[$attribute];

                if ($model->isCustomDateTimeCast($cast) || $model->isImmutableCustomDateTimeCast($cast)) {
                    $changes[$attribute] = $model->asDateTime($changes[$attribute])->format(explode(':', $cast, 2)[1]);
                }
            }
        }

        return $changes;
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return null[]
     */
    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        $relatedModelNames = explode('.', $attribute);
        $relatedAttribute = array_pop($relatedModelNames);

        $attributeName = [];
        $relatedModel = $model;

        do {
            $attributeName[] = $relatedModelName = static::getRelatedModelRelationName($relatedModel, array_shift($relatedModelNames));

            $relatedModel = $relatedModel->$relatedModelName ?? $relatedModel->$relatedModelName();
        } while (! empty($relatedModelNames));

        $attributeName[] = $relatedAttribute;

        return [implode('.', $attributeName) => $relatedModel->$relatedAttribute ?? null];
    }

    /**
     * @param Model $model
     * @param string $relation
     * @return string
     */
    protected static function getRelatedModelRelationName(Model $model, string $relation): string
    {
        return Arr::first([
            $relation,
            Str::snake($relation),
            Str::camel($relation),
        ], function (string $method) use ($model): bool {
            return method_exists($model, $method);
        }, $relation);
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return mixed
     */
    protected static function getModelAttributeJsonValue(Model $model, string $attribute): mixed
    {
        $path = explode('->', $attribute);
        $modelAttribute = array_shift($path);
        $modelAttribute = collect($model->getAttribute($modelAttribute));

        return data_get($modelAttribute, implode('.', $path));
    }
}