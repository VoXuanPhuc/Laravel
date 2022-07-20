<?php

namespace Encoda\Identity\Http\Requests\UserGroup;

use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Http\Request;

/**
 * @property string $id
 * @property string desc
 * @property int precedence
 */
class UpdateUserGroupRequest extends FormRequest
{

    /**
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'desc' => 'required'
        ];
    }

    /**
     * @return array
     */
    protected function messages(): array
    {
        return [

        ];
    }
}
