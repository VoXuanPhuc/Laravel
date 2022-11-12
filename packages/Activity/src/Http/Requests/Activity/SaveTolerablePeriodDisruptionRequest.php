<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $tolerable_period_disruption
 */
class SaveTolerablePeriodDisruptionRequest extends FormRequest
{


    protected function rules(): array
    {
        return [
            "tolerable_period_disruptions" => "required|array",
            "tolerable_period_disruptions.*.uid" => "required",
            "tolerable_period_disruptions.*.dependent_time" => "nullable|string|max:1023",
            "tolerable_period_disruptions.*.reason_choose_dependent_time" => "nullable|string:max:1023",
        ];
    }
}
