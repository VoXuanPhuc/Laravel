<?php

namespace Encoda\Identity\Http\Requests\User;

use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Http\Request;

/**
 * @property string $name
 * @property string desc
 * @property int precedence
 */
class UpdateUserRequest extends FormRequest
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
