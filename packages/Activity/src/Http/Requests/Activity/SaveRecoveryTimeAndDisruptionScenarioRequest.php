<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property $recovery_times[]
 * @property $disruption_scenarios[]
 */
class SaveRecoveryTimeAndDisruptionScenarioRequest extends FormRequest
{


    protected function rules(): array
    {
        return [
            "recovery_times" => "required|array",
            "recovery_times.*.uid" => "required",
            "recovery_times.*.is_rto_tested" => "required|boolean",
            "disruption_scenarios" => "required|array",
            "disruption_scenarios.*.uid" => "required",
            "disruption_scenarios.*.workaround_solution" => "nullable|string",
            "disruption_scenarios.*.workaround_feasibly" => "nullable|string",
        ];
    }
}
