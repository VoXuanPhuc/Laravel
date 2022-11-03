<?php

namespace Encoda\AWSCognito\Services;

use Encoda\AWSCognito\Enums\CognitoErrorCode;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Log;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Http\Request;

class CognitoPasswordService extends CognitoBaseService
{


    /**
     * @param $data
     * @return mixed|null
     * @throws BadRequestException
     */
    public function forgotPassword( $data ) {

        $username = $data['email'];

        try {
            $result = $this->cognitoClient->getClient()->forgotPassword(
                [
                    'ClientId' => $this->cognitoClient->getClientId(),
                    'SecretHash' => $this->cognitoSecretHash( $username ),
                    'UserContextData' => [
                        'IpAddress' => request()->ip(),
                    ],
                    'Username' => $username,
                ]
            );
        }
        catch (CognitoIdentityProviderException $e) {
            Log::error( $e );
            if ($e->getAwsErrorCode() === CognitoErrorCode::USER_NOT_FOUND) {
                throw new NotFoundException('User not found' );
            }
            throw new BadRequestException( $e->getAwsErrorMessage() );
        }

        return $result->get('@metadata');
    }

    /**
     * @param $data
     * @return mixed|null
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function confirmForgotPassword( $data ) {

        $username = $data['email'];
        $newPassword = $data['new_password'];
        $confirmationCode = $data['confirmation_code'];
        try {
            $result = $this->cognitoClient->getClient()->confirmForgotPassword(
                [
                    'ClientId' => $this->cognitoClient->getClientId(),
                    'SecretHash' => $this->cognitoSecretHash( $username ),
                    'UserContextData' => [
                        'IpAddress' => request()->ip(),
                    ],
                    'ConfirmationCode' => $confirmationCode,
                    'Username' => $username,
                    'Password' => $newPassword
                ]
            );
        }
        catch (CognitoIdentityProviderException $e) {
            Log::error( $e );
            switch ($e->getAwsErrorCode()) {
                case CognitoErrorCode::CODE_MISMATCH:
                    throw new BadRequestException('Your code is incorrect' );
                case CognitoErrorCode::USER_NOT_FOUND:
                    throw new NotFoundException('User not found' );
            }

            throw new BadRequestException( $e->getAwsErrorMessage() );
        }

        return $result->get('@metadata');
    }


}
