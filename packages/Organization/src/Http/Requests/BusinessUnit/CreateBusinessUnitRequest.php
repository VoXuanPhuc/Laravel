<?php

namespace Encoda\Organization\Http\Requests\BusinessUnit;

use Encoda\Core\Http\Requests\FormRequest;

class CreateBusinessUnitRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }
}
