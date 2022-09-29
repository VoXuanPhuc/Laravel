<?php

namespace Encoda\Resource\Http\Requests\Owner;

use Encoda\Core\Http\Requests\FormRequest;

class UpdateResourceOwnerRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'is_invite' => 'required|boolean',
        ];
    }
}
