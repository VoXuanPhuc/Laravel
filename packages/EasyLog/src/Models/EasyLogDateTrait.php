<?php

namespace Encoda\EasyLog\Models;

use Carbon\Carbon;

trait EasyLogDateTrait
{
    public function initializeEasyLogDateTrait() {
        $this->append('date' );
    }

    public function getDateAttribute() {

        $diffDate = $this->created_at->diffInDays( Carbon::now() );

        if( $diffDate == 0 ) {
            return 'Today';
        }

        if( $diffDate == 1 ) {
            return 'Yesterday';
        }


        // Format date time
        return $this->created_at->format( 'd-m-Y') . ' at ' . $this->created_at->format( 'h:s:m A');
    }
}
