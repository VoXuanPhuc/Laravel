<?php

namespace Encoda\EasyLog\Models;

use Encoda\Core\Helpers\StringHelper;
use Encoda\Identity\Models\Database\User;

trait EasyLogCauserTrait
{
    protected $avatarColors = [];

    protected $defaultColors = [
        'bg-c1-600',
        'bg-c2-600',
        'bg-c3-100',
        'bg-c3-400',
    ];

    public function initializeEasyLogCauserTrait() {
        $this->append('causer_summary' );
    }


    /**
     * @return array|string[]|void
     */
    public function getCauserSummaryAttribute() {
        return $this->getCauserSummary();
    }

    /**
     * @return array|string[]
     */
    public function getCauserSummary() {

        $default = [
            'name' => 'Anonymous',
            'short' => 'AN'
        ];

        $causer = $this->causer()->getResults();

        switch ( get_class( $causer ) ) {
            case User::class:
                return [
                    'uid' => $this->causer->uid,
                    'name' => $this->causer->name,
                    'short' => StringHelper::firstLetters( $this->causer->name ),
                    'color' => $this->generateAvatarColor( $this->causer->uid ),
                ];
            default:
                return $default;
        }

    }

    /**
     * @param $uid
     * @return array|int|mixed|string
     */
    protected function generateAvatarColor( $uid ) {
        if( isset( $this->avatarColors[$uid] ) ) {
            return $this->avatarColors[$uid];
        }

        $color = $this->defaultColors[array_rand( $this->defaultColors, 1 )];

        $this->avatarColors[$uid] = $color;

        return $color;
    }
}
