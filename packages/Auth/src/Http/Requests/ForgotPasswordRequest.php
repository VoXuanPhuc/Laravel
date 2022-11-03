<?php

namespace Encoda\Auth\Http\Requests;

use Encoda\Core\Http\Requests\FormRequest;

class ForgotPasswordRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
