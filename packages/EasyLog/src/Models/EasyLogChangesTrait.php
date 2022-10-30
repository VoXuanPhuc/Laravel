<?php

namespace Encoda\EasyLog\Models;

use Encoda\EasyLog\Enums\LogEventEnum;

trait EasyLogChangesTrait
{
    protected $ignores = [
        'deleted_at',
        'updated_at',
    ];

    public  function initializeEasyLogChangesTrait() {
        $this->append( 'change_summary' );
    }

    public function getChangeSummaryAttribute() {


        if( $this->event == LogEventEnum::CREATED ) {

            return [
                [
                    'subject' => $this->subject_type,
                    'old' =>  null,
                    'new' => null,
                ]
            ];
        }

        // Other cases
        $oldAttributes = $this->changes()->get('old');

        $rtn = [];
        foreach ( (array)$this->changes()->get('attributes') as $newAttrKey => $newAttrValue ) {

            // No need to show updated_at
            if( in_array( $newAttrKey, $this->ignores ) ) {
                continue;
            }

            $oldAttributeValue =  $oldAttributes[$newAttrKey] ?? null;

            /**
             * If the property is an enum then convert to friendly names
             */
            $subject = $this->subject()->getResults();

            if( $subject->hasCast($newAttrKey ) ) {
                $enumClass = $this->subject->getCasts()[$newAttrKey];

                if( enum_exists( $enumClass ) ) {
                    $newAttrValue = $enumClass::from( $newAttrValue )->friendlyName();

                    //Old Attribute
                    $oldAttributeValue =  $oldAttributeValue
                        ? $enumClass::from( $oldAttributeValue )->friendlyName()
                        : 'None';
                }
            }

            array_push( $rtn, [
                'subject' => $subject->getAttributeName( $newAttrKey ),
                'old' =>  $oldAttributeValue ?: 'None',
                'new' => $newAttrValue,
            ]);
        }

        return $rtn;
    }
}
