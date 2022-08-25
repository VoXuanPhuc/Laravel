<?php

namespace Encoda\Rbac\Http\Requests\RolePermission;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property string permission_uid
 */
class RoleAddPermissionRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'permission_uid' => 'required',
        ];
    }
}
