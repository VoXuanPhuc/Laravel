<?php

namespace Encoda\Resource\Validations;

use Illuminate\Contracts\Validation\Rule;

class ResourceCategoryDataValidation implements Rule
{

    public function passes($attribute, $value)
    {
        return strlen( $value['uid'] ) > 0;
    }

    public function message()
    {
        return 'Resource must belong to a category';
    }
}
