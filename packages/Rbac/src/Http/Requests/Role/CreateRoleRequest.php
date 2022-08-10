<?php

namespace Encoda\Rbac\Http\Requests\Role;

use Encoda\Core\Http\Requests\FormRequest;

class CreateRoleRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
        ];
    }
}
