<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ITSolutionContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ITSolution extends Model implements ITSolutionContract
{
    protected $fillable = [
        'uid',
        'location',
        'data_type',
        'activity_id',
    ];

    /**
     * @return BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

}
