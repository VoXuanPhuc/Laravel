<?php

namespace Encoda\Activity\Validations;

use Illuminate\Contracts\Validation\Rule;

class ActivityDivisionOrBusinessUnitRequiredValidation implements Rule
{

    protected $businessUnit;

    public function __construct( $businessUnit )
    {
        $this->businessUnit = $businessUnit;
    }

    /**
     * @return bool
     */
    protected function isBusinessUnitEmpty() {
        return empty( $this->businessUnit )
                || empty( $this->businessUnit['uid'] )
                || strlen( $this->businessUnit['uid'] ) <= 0;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if( empty( $value['uid'])  && $this->isBusinessUnitEmpty() ) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Activity must belong to a division or business unit';
    }
}
