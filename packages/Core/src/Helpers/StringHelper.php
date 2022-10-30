<?php

namespace Encoda\Core\Helpers;

use Illuminate\Support\Str;

class StringHelper
{

    /**
     * @param $str
     * @return string
     */
    public static function firstLetters( $str ) {

        $words = explode(' ', trim( $str ) );

        $firstLetters = array_map( function( $word ) {

            $first = substr( $word, 0, 1 );
            if( strlen( $first ) > 0 ) {
                return strtoupper( $first );
            }

            return null;
        }, $words );

        return implode( '', $firstLetters );
    }
}
