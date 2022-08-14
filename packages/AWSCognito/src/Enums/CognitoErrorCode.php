<?php

namespace Encoda\AWSCognito\Enums;

final class CognitoErrorCode
{

    const NEW_PASSWORD_CHALLENGE = 'NEW_PASSWORD_REQUIRED';
    const FORCE_PASSWORD_STATUS  = 'FORCE_CHANGE_PASSWORD';
    const RESET_REQUIRED         = 'PasswordResetRequiredException';
    const USER_NOT_FOUND         = 'UserNotFoundException';
    const USERNAME_EXISTS        = 'UsernameExistsException';
    const INVALID_PASSWORD       = 'InvalidPasswordException';
    const CODE_MISMATCH          = 'CodeMismatchException';
    const EXPIRED_CODE           = 'ExpiredCodeException';
}
