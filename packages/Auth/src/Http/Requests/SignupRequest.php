<?php
namespace Encoda\Auth\Http\Requests;

use ConfirmPasswordMatchValidation;
use Encoda\Core\Http\Requests\BaseRequest;

class SignupRequest extends BaseRequest
{
    public string $email;
    public string $password;
    public string $confirmPassword;
    public string $firstName;
    public string $lastName;


    public function rules()
    {
        return [
            'email'     => 'required|max:255',
            'password'  => 'required|min:8|max:255',
            'confirmPassword'  => ['required|min:8|max:255', new ConfirmPasswordMatchValidation( $this->password, $this->confirmPassword)],
            'firstName' => ['']
        ];
    }
}
