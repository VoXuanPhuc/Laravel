<?php
namespace Encoda\Auth\Http\Requests;

use Encoda\Core\Http\Requests\FormRequest;
use Encoda\Identity\Validations\ConfirmPasswordMatchValidation;

class SignupRequest extends FormRequest
{
    public string $username;
    public string $password;
    public string $confirmPassword;
    public string $firstName;
    public string $lastName;


    public function rules() : array
    {
        return [
            'username'     => 'required|max:255',
            'password'  => 'required|min:8|max:255',
            'confirmPassword'  => ['required', new ConfirmPasswordMatchValidation( $this->get('password'), $this->get('confirmPassword'))],
            'firstName' => ['']
        ];
    }
}
