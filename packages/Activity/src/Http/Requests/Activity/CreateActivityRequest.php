<?php

namespace Encoda\Activity\Http\Requests\Activity;

use Encoda\Core\Http\Requests\FormRequest;

class CreateActivityRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
        ];
    }
}
