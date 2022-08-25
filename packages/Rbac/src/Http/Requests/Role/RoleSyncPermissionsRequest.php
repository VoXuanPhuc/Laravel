<?php

namespace Encoda\Rbac\Http\Requests\Role;

use Encoda\Core\Http\Requests\FormRequest;

/**
 * @property array $permission_uids
 */
class RoleSyncPermissionsRequest extends FormRequest
{

    protected function rules(): array
    {
        return [];
    }
}
