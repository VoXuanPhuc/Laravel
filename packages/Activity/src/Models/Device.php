<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\DeviceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model implements DeviceContract
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
