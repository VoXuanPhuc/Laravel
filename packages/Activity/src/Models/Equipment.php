<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\DeviceContract;
use Encoda\Core\Models\Model;
use Encoda\Notification\Traits\NotifySender;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model implements DeviceContract
{
    use SoftDeletes;
    use NotifySender;

    protected $table = 'equipments';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'uid',
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];

    /**
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
