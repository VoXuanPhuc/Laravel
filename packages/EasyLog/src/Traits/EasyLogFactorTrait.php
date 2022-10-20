<?php

namespace Encoda\EasyLog\Traits;

use Encoda\EasyLog\Exceptions\CouldNotLogAction;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * @property $easyLog
 */
trait EasyLogFactorTrait
{

    /**
     * @param Model|int|string|null $modelOrId
     * @return $this
     * @throws CouldNotLogAction
     * @throws Throwable
     */
    public function causedBy(Model | int | string | null $modelOrId): static
    {
        if ($modelOrId === null) {
            return $this;
        }

        $model = $this->causerResolver->resolve($modelOrId);

        $this->getEasyLog()->causer()->associate($model);

        return $this;
    }

    /**
     * @param Model|int|string|null $modelOrId
     * @return $this
     * @throws CouldNotLogAction|Throwable
     */
    public function by(Model | int | string | null $modelOrId): static
    {
        return $this->causedBy($modelOrId);
    }

    /**
     * @return $this
     */
    public function causedByAnonymous(): static
    {
        $this->easyLog->causer_id = null;
        $this->easyLog->causer_type = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function byAnonymous(): static
    {
        return $this->causedByAnonymous();
    }

}
