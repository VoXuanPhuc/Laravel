<?php

namespace Encoda\Identity\Http\Requests\Admin;

use Encoda\Core\Http\Requests\FormRequest;

class AdminCreateUserRequest extends FormRequest
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $roleId;

    protected function rules(): array
    {
        return [
            'username'     => 'required|max:255',
            'firstName' => ['required'],
            'lastName' => ['required'],
//            'roleId' => ['required'],
        ];
    }
}
