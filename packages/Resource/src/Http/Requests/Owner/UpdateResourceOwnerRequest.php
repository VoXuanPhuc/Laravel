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
            'email' => 'required_if:is_invite,true|nullable|max:255|email|unique:resource_owners,email,'. $this->uid . ',uid',
            'is_invite' => 'required|boolean',
        ];
    }
}
