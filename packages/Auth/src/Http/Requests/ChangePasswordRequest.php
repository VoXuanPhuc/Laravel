<?php

namespace Encoda\Auth\Http\Requests;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Core\Validations\ConfirmPasswordMatchValidation;

/**
 * @property $username
 * @property $session_value
 * @property $current_password
 * @property $new_password
 * @property $confirm_password
 */
class ChangePasswordRequest extends FormRequest
{

    protected function rules(): array
    {
        return [
            'username' => 'required',
            'session_value' => 'required',
            'first_name' => 'required',
            'new_password'  => 'required|min:8|max:255',
            'confirm_password'  => ['required', new ConfirmPasswordMatchValidation( $this->get('new_password'), $this->get('confirm_password'))],
        ];
    }
}
