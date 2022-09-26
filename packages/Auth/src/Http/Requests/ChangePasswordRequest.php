<?php

namespace Encoda\Auth\Http\Requests;

use Encoda\Core\Http\Requests\FormRequest;

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
        return [];
    }
}
