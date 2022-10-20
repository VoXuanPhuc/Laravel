<?php

namespace Encoda\EasyLog;

use Closure;
use DateTimeInterface;
use Encoda\EasyLog\Entities\EasyLogStatus;
use Encoda\EasyLog\Entities\LogBatch;
use Encoda\EasyLog\Providers\EasyLogServiceProvider;
use Encoda\EasyLog\Resolvers\CauserResolver;
use Encoda\EasyLog\Traits\EasyLogEventTrait;
use Encoda\EasyLog\Traits\EasyLogFactorTrait;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Encoda\EasyLog\Contracts\EasyLog as EasyLogContract;
use Throwable;

class EasyLogger
{
    use Conditionable;
    use Macroable;
    use EasyLogFactorTrait, EasyLogEventTrait;

    protected ?string $defaultLogName = null;

    protected CauserResolver $causerResolver;

    protected EasyLogStatus $logStatus;

    protected ?EasyLogContract $easyLog = null;

    protected LogBatch $batch;

    /**
     * @param Repository $config
     * @param EasyLogStatus $logStatus
     * @param LogBatch $batch
     * @param CauserResolver $causerResolver
     */
    public function __construct(
        Repository $config,
        EasyLogStatus $logStatus,
        LogBatch $batch,
        CauserResolver $causerResolver
    )
    {
        $this->causerResolver = $causerResolver;

        $this->batch = $batch;

        $this->defaultLogName = $config['easylog']['default_log_name'];

        $this->logStatus = $logStatus;
    }

    public function setLogStatus(EasyLogStatus $logStatus): static
    {
        $this->logStatus = $logStatus;

        return $this;
    }

    /**
     * @param Model $model
     * @return $this
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function performedOn(Model $model): static
    {
        $this->getEasyLog()->subject()->associate($model);

        return $this;
    }

    /**
     * @param Model $model
     * @return $this
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function on(Model $model): static
    {
        return $this->performedOn($model);
    }

    /**
     * @param mixed $properties
     * @return $this
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function withProperties(mixed $properties): static
    {
        $this->getEasyLog()->properties = collect($properties);

        return $this;
    }

    /**
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function withProperty(string $key, mixed $value): static
    {
        $this->getEasyLog()->properties = $this->getEasyLog()->properties->put($key, $value);

        return $this;
    }

    /**
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function createdAt(DateTimeInterface $dateTime): static
    {
        $this->getEasyLog()->created_at = Carbon::instance($dateTime);

        return $this;
    }

    /**
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function useLog(?string $logName): static
    {
        $this->getEasyLog()->log_name = $logName;

        return $this;
    }

    /**
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function inLog(?string $logName): static
    {
        return $this->useLog($logName);
    }

    /**
     * @param callable $callback
     * @param string|null $eventName
     * @return $this
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function tap(callable $callback, string $eventName = null): static
    {
        call_user_func($callback, $this->getEasyLog(), $eventName);

        return $this;
    }

    /**
     * @return $this
     */
    public function enableLogging(): static
    {
        $this->logStatus->enable();

        return $this;
    }

    /**
     * @return $this
     */
    public function disableLogging(): static
    {
        $this->logStatus->disable();

        return $this;
    }

    /**
     * @param string $description
     * @return EasyLogContract|null
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    public function log(string $description): ?EasyLogContract
    {
        if ($this->logStatus->disabled()) {
            return null;
        }

        $easyLog = $this->easyLog;

        $easyLog->description = $this->replacePlaceholders(
            $easyLog->description ?? $description,
            $easyLog
        );

        if (isset($easyLog->subject) && method_exists($easyLog->subject, 'tapEasyLog')) {
            $this->tap([$easyLog->subject, 'tapEasyLog'], $easyLog->event ?? '');
        }

        $easyLog->save();

        $this->easyLog = null;

        return $easyLog;
    }

    /**
     * @param Closure $callback
     * @return mixed
     */
    public function withoutLogs(Closure $callback): mixed
    {
        if ($this->logStatus->disabled()) {
            return $callback();
        }

        $this->logStatus->disable();

        try {
            return $callback();
        } finally {
            $this->logStatus->enable();
        }
    }

    /**
     * @param string $description
     * @param EasyLogContract $easyLog
     * @return string
     */
    protected function replacePlaceholders(string $description, EasyLogContract $easyLog): string
    {
        return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) use ($easyLog) {
            $match = $match[0];

            $attribute = Str::before(Str::after($match, ':'), '.');

            if (! in_array($attribute, ['subject', 'causer', 'properties'])) {
                return $match;
            }

            $propertyName = substr($match, strpos($match, '.') + 1);

            $attributeValue = $easyLog->$attribute;

            if (is_null($attributeValue)) {
                return $match;
            }

            return data_get($attributeValue, $propertyName, $match);
        }, $description);
    }

    /**
     * @throws Exceptions\CouldNotLogAction|Throwable
     */
    protected function getEasyLog(): EasyLogContract
    {
        if (! $this->easyLog instanceof EasyLogContract) {
            $this->easyLog = EasyLogServiceProvider::getEasyLogModelInstance();
            $this
                ->useLog($this->defaultLogName)
                ->withProperties([])
                ->causedBy($this->causerResolver->resolve());

            $this->easyLog->batch_uuid = $this->batch->getUuid();
        }

        return $this->easyLog;
    }
}
