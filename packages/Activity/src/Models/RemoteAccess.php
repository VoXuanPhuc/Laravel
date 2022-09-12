<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\RemoteAccessContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RemoteAccess extends Model implements RemoteAccessContract
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
