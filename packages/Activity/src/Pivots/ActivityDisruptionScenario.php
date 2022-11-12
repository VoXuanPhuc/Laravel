<?php

namespace Encoda\Activity\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 *
 */
class ActivityDisruptionScenario extends Pivot
{
    /**
     * @var string[]
     */
    protected $hidden = [
        'activity_id',
        'disruption_scenario_id'
    ];
}
