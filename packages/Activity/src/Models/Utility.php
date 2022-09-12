<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\UtilityContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Utility extends Model implements UtilityContract
{
    protected $fillable = [
        'uid',
        'name',
        'description',
    ];

    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
