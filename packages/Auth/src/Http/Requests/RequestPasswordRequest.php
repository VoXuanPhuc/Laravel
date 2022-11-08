<?php

namespace Encoda\Auth\Http\Requests;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Core\Validations\ConfirmPasswordMatchValidation;

class RequestPasswordRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'username' => 'required',
            'confirmation_code' => 'required',
            'new_password'  => 'required|min:8|max:255',
            'confirm_password'  => ['required', new ConfirmPasswordMatchValidation( $this->get('new_password'), $this->get('confirm_password'))],
        ];
    }
}
