<?php

namespace Encoda\Rbac\Http\Requests\RolePermission;

use Encoda\Core\Http\Requests\FormRequest;

class RolePermissionRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'permission_id' => 'required|numeric',
        ];
    }
}
