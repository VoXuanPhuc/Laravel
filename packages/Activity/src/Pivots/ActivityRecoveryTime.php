<?php

namespace Encoda\Activity\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 *
 */
class ActivityRecoveryTime extends Pivot
{
    /**
     * @var string[]
     */
    protected $hidden = [
        'activity_id',
        'recovery_time_id'
    ];

    protected $casts = [
        'is_rto_tested' => 'boolean'
    ];
}
