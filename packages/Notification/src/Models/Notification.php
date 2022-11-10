<?php

namespace Encoda\Notification\Models;

use Carbon\Carbon;
use Encoda\Core\Models\Model;
use Encoda\MultiTenancy\Traits\MultiTenancyModel;

/**
 * @property Carbon $created_at
 */
class Notification extends Model
{

    use MultiTenancyModel;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $hidden = [
        'notifiable_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'time',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'notifications';

    /**
     * @var string[]
     */
    protected $casts = [
        'pinned' => 'boolean',
        'read_at' => 'datetime',

    ];

    /**
     * Mark as read
     */
    public function markAsRead() {
        $this->read_at = Carbon::now();
        $this->update();
    }

    /**
     * @return mixed
     */
    public function getTimeAttribute() {

        $now = Carbon::now();

        if( $now->timestamp - $this->created_at->timestamp < 60 ) {
            return 'Now';
        }

        $diff = $this->created_at->diff( $now );

        if( $diff->days == 0 ) {
            return 'Today';
        }
        if( $diff->days == 1 ) {
            return 'Yesterday';
        }
        return "{$diff->days} days ago";
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}
