<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ApplicationContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model implements ApplicationContract
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
