<?php

namespace Encoda\Notification\Models;

use Carbon\Carbon;
use Encoda\Core\Models\Model;

/**
 * @property Carbon $created_at
 */
class Notification extends Model
{

    protected $hidden = [
        'id',
        'notifiable_id',
    ];

    protected $appends = [
        'time',
    ];

    protected $table = 'notifications';


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
}
