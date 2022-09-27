<?php

namespace Encoda\Core\Validations;

class ConfirmPasswordMatchValidation implements \Illuminate\Contracts\Validation\Rule
{

    protected $password;
    protected $confirmPassword;

    public function __construct( $password, $confirmPassword )
    {
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes( $attribute, $value ) {
        return trim($this->password) == trim($this->confirmPassword);
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Confirm password does not match';
    }
}
