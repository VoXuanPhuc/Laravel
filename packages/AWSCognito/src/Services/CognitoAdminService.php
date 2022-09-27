<?php

namespace Encoda\AWSCognito\Services;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Encoda\AWSCognito\Enums\CognitoErrorCode;
use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Exceptions\NotFoundException;
use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CognitoAdminService extends CognitoBaseService
{

    /**
     * @param $username
     * @return CognitoUser
     * @throws BadRequestException
     */
    public function adminGetUser( $username ): CognitoUser
    {
        try {
            $result = $this->cognitoClient->getClient()->adminGetUser(
                [
                    'Username' => $username,
                    'UserPoolId' => $this->cognitoClient->getUserPoolId(),
                ]
            );
        }

        catch ( CognitoIdentityProviderException $e ) {
            if( $e->getAwsErrorCode() == CognitoErrorCode::USER_NOT_FOUND ) {
                throw new BadRequestException( 'User not found ');
            }

            throw $e;
        }


        return CognitoUser::transformUser( $result );
    }


    /**
     * @param array $data
     * @return mixed|null
     * @throws BadRequestException
     * @throws Exception
     */
    public function adminCreateUser( $data = [] ) {


        $transformedAttributes = [
            [
                'Name' => 'given_name',
                'Value' => $data['firstName'],
            ],
            [
                'Name' => 'family_name',
                'Value' => $data['lastName'],
            ],
            [
                'Name' => 'email_verified',
                'Value' => 'false',
            ],

            [
                'Name' => 'email',
                'Value' => $data['username'],
            ],
            [
                'Name' => 'custom:client_id',
                'Value' => $this->cognitoClient->getClientId(),
            ],
        ];

        $tempPassword = $this->generateTempPassword();
        try {
            $result = $this->cognitoClient->getClient()->adminCreateUser(
                [
                    'UserAttributes' => $transformedAttributes,
                    'Username' => $data['username'],
                    'UserPoolId' => $this->cognitoClient->getUserPoolId(),
                    'TemporaryPassword' => $tempPassword,
                ]
            );
        }
        catch ( CognitoIdentityProviderException $e ) {

            Log::error($e->getMessage());
            switch ( $e->getAwsErrorCode() )
            {
                case CognitoErrorCode::USERNAME_EXISTS:
                    throw new BadRequestException('Selected username is existed');
                    break;

                default:
                    throw new Exception('Unable to process request');
                    break;
            }



        }

        $cognitoUser = CognitoUser::transformFromAdminCreateUser($result->get('User') );
        $cognitoUser->password = $tempPassword;

        Event::dispatch( 'identity.cognito.user.create.after', $cognitoUser );

        return $cognitoUser;
    }


    /**
     * @param $username
     * @param array $data
     * @return mixed|null
     */
    public function adminUpdateUser($username, array $data = [] )
    {

        $transformedAttributes = [
            [
                'Name' => 'given_name',
                'Value' => $data['firstName'],
            ],
            [
                'Name' => 'family_name',
                'Value' => $data['lastName'],
            ],
        ];

        $result = $this->cognitoClient->getClient()->adminUpdateUserAttributes(
            [
                'UserAttributes' => $transformedAttributes,
                'Username' => $username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $result->get('@metadata');
    }


    public function adminDeleteUser( $username ) {

        try {
            $result = $this->cognitoClient->getClient()->adminDeleteUser(
                [
                    'Username' => $username,
                    'UserPoolId' => $this->cognitoClient->getUserPoolId(),
                ]
            );
        }
        catch ( CognitoIdentityProviderException $e ) {
            if( $e->getAwsErrorCode() == CognitoErrorCode::USER_NOT_FOUND ) {
                throw new NotFoundException('User not found' );
            }

            throw $e;
        }

        return $result->get('@metadata');
    }
    /**
     * @return string
     */
    private function generateTempPassword(): string
    {
        $nums = '0123456789';
        $lowerCases = 'abcdefghijklmnopqrstuvwxyz';
        $upperCases ='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $specials = '$*.[]{}()?-"!@#%&/\,><\':;|_~`+=';

        return $this->generateRandonStr( $upperCases )
            . $this->generateRandonStr( $nums )
            . $this->generateRandonStr( $lowerCases )
            . $this->generateRandonStr( $specials )
            ;
    }

    /**
     * @param $characters
     * @param int $length
     * @return string
     */
    private function generateRandonStr( $characters, int $length = 3 ): string
    {

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
