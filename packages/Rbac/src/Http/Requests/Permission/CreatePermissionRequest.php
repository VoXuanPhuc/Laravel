<?php

namespace Encoda\Rbac\Http\Requests\Permission;

use Encoda\Core\Http\Requests\FormRequest;

class CreatePermissionRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'name' => 'required|max:125',
        ];
    }
}
