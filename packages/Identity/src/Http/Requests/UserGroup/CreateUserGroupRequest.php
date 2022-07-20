<?php

namespace Encoda\Identity\Http\Requests\UserGroup;

use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Http\Request;

/**
 * @property string $name
 * @property string desc
 * @property int precedence
 */
class CreateUserGroupRequest extends FormRequest
{

    /**
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'name' => 'required',
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
