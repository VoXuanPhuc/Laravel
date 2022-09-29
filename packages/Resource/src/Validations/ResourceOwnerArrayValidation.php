<?php

namespace Encoda\Resource\Validations;

use Illuminate\Contracts\Validation\Rule;

class ResourceOwnerArrayValidation implements Rule
{

    protected $errorMessage = 'At least 1 resource owner required';

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if( empty( $value ) ) {
            return false;
        }
        foreach ( $value as $owner ) {
            if( strlen($owner['uid']) <= 0 ) {
                $this->errorMessage = 'Owner uid must be a valid string';
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}
