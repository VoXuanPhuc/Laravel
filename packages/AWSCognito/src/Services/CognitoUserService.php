<?php
namespace Encoda\AWSCognito\Services;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\AWSCognito\Models\CognitoUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class CognitoUserService extends CognitoBaseService
{

    const NEW_PASSWORD_CHALLENGE = 'NEW_PASSWORD_REQUIRED';
    const FORCE_PASSWORD_STATUS  = 'FORCE_CHANGE_PASSWORD';
    const RESET_REQUIRED         = 'PasswordResetRequiredException';
    const USER_NOT_FOUND         = 'UserNotFoundException';
    const USERNAME_EXISTS        = 'UsernameExistsException';
    const INVALID_PASSWORD       = 'InvalidPasswordException';
    const CODE_MISMATCH          = 'CodeMismatchException';
    const EXPIRED_CODE           = 'ExpiredCodeException';

    protected array $defaultAttributes = [
        'email',
        'custom:client_id',
        'given_name',
        'family_name',
        'email_verified',

    ];

    public function __construct(AWSCognitoClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param $username
     * @param $password
     * @return array
     * @throws UserNotFoundException
     */
    public function authenticate( $username, $password ) {

        try {
            $response = $this->cognitoClient->getClient()->adminInitiateAuth(
                [
                    'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                    'AuthParameters' => [
                        'USERNAME'     => $username,
                        'PASSWORD'     => $password,
                        'SECRET_HASH'  => $this->cognitoSecretHash($username)
                    ],
                    'ClientId'   => $this->cognitoClient->getClientId(),
                    'UserPoolId' => $this->cognitoClient->getUserPoolId()
                ]
            );
        }
        catch ( CognitoIdentityProviderException $exception ) {

            if ($exception->getAwsErrorCode() === self::RESET_REQUIRED ) {
                //TODO: Handle reset required and other exceptions here
                return false;
            }

            throw new UserNotFoundException();
        }

        $authResult = $response->get('AuthenticationResult');

        //Transform result
        return [
            'accessToken' => $authResult['AccessToken'],
            'expiresIn' => $authResult['ExpiresIn'],
            'idToken' => $authResult['IdToken'],
            'refreshToken' => $authResult['RefreshToken'],
        ];
    }

    /**
     * @param array $data
     * @return CognitoUser
     */
    public function register( array $data = [] ) {

        $username = $data['username'];
        $password = $data['password'];

        // User attributes
        $attributes['given_name'] = $data['firstName'];
        $attributes['family_name'] = $data['lastName'];

        //Always set client id to custom attributes
        $attributes['custom:client_id'] = $this->cognitoClient->getClientId();

        try {
            $response = $this->cognitoClient->getClient()->signUp(
                [
                    'ClientId' => $this->cognitoClient->getClientId(),
                    'Password' => $password,
                    'SecretHash' => $this->cognitoSecretHash( $username ),
                    'UserAttributes' => $this->formatAttributes( $attributes ),
                    'Username' => $username,
                ]
            );
        }
        catch ( CognitoIdentityProviderException $e ) {

            Log::error($e->getMessage());
            throw $e;
        }

        $cognitoUser = new CognitoUser(
            [
                'id' => $response->get('UserSub'),
                'username' => $username,
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
            ]
        );

        $cognitoUser->password = $password;


        return $cognitoUser;
    }

    /**
     * @param $username
     * @return string
     */
    public function sendResetLink( $username ) {
        try {

            $result = $this->cognitoClient->getClient()->forgotPassword([
                'ClientId' => $this->cognitoClient->clientId,
                'SecretHash' => $this->cognitoSecretHash( $username ),
                'Username' => $username,
            ]);

        } catch (CognitoIdentityProviderException $e) {
            if ($e->getAwsErrorCode() === self::USER_NOT_FOUND) {
                return Password::INVALID_USER;
            }

            throw $e;
        }

        return Password::RESET_LINK_SENT;
    }

    /**
     * @param $username
     * @param $confirmationCode
     * @param $ip
     * @return \Aws\Result
     */
    public function confirmSignup( $username, $confirmationCode, $ip ) {

        $result = $this->cognitoClient->getClient()->confirmSignUp([
//            'AnalyticsMetadata' => [
//                'AnalyticsEndpointId' => '',
//            ],
            'ClientId' => $this->cognitoClient->getClientId(),
            //'ClientMetadata' => [''],
            'ConfirmationCode' => $confirmationCode,
            'ForceAliasCreation' => true,
            'SecretHash' => $this->cognitoSecretHash( $username ),
            'UserContextData' => [
//                'EncodedData' => '',
                'IpAddress' => $ip,
            ],
            'Username' => $confirmationCode
        ]);

        return $result;
    }

    /**
     * @param array $attributes
     * @param string $filter
     * @param int $limit
     * @param int|string $pagination
     * @return array
     */
    public function listUsers(array $attributes = [], string $filter = '', int $limit = 60, int|string $pagination = '' ): array
    {

        $result = $this->cognitoClient->getClient()->listUsers([
                    'AttributesToGet' => empty( $attributes ) ? $this->defaultAttributes : $attributes,
                    'Filter' => $filter,
                   // 'Limit' => $limit,
                   // 'PaginationToken' => $pagination,
                    'UserPoolId' => $this->cognitoClient->getUserPoolId(),
                ]);

        $listUsers = [];
        foreach ( $result->get('Users') as $cognitoUser ) {
            array_push( $listUsers, CognitoUser::transformUserFromList( $cognitoUser ) );
        }

        return $listUsers;
    }

    public function delete( $token )
    {
        $result = $this->cognitoClient->getClient()->deleteUser(
            [
                'AccessToken' => $token
            ]
        );

        return $result->get('@metadata');
    }


}
