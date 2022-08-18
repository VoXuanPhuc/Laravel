<?php

namespace Encoda\Rbac\Http\Requests\Role;

use Encoda\Core\Http\Requests\FormRequest;

class RoleSyncPermissionsRequest extends FormRequest
{

    public array $permissionUids = [];

    protected function rules(): array
    {
        return [];
    }
}
