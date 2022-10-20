<?php
namespace Encoda\AWSCognito\Services;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Encoda\Auth\DTOs\AuthChallengeDTO;
use Encoda\Auth\DTOs\TokenDTO;
use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\Core\Exceptions\BadRequestException;
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
     * @return TokenDTO | AuthChallengeDTO
     * @throws UserNotFoundException
     */
    public function authenticate( $username, $password ): TokenDTO | AuthChallengeDTO
    {

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
                return (new AuthChallengeDTO())->challengeName( self::RESET_REQUIRED );
            }

            throw new UserNotFoundException();
        }

        $authResult = $response->get('AuthenticationResult');

        // Check for login challenge
        $challengeName = $response->get('ChallengeName');

        if(  strlen( $challengeName ) > 0  ) {

            $challengeParams = $response->get('ChallengeParameters');
            $userAttributes = json_decode( $challengeParams['userAttributes']);

            return ( new AuthChallengeDTO() )
                ->challengeName( $challengeName )
                ->session( $response->get('Session') )
                ->userUid( $challengeParams['USER_ID_FOR_SRP'] )
                ->firstName( $userAttributes->given_name );

        }

        //Transform result
        return ( new TokenDTO() )
            ->accessToken( $authResult['AccessToken'] )
            ->expiresIn( $authResult['ExpiresIn'] )
            ->idToken( $authResult['IdToken'] )
            ->refreshToken( $authResult['RefreshToken'] );
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
     * @param $data
     * @return mixed|null
     */
    public function changePassword( $data ) {
        $result = $this->cognitoClient->getClient()->changePassword(
            [
                'AccessToken' => $data['token'],
                'PreviousPassword' => $data['currentPassword'],
                'ProposedPassword' => $data['newPassword'],
            ]
        );

        return $result->get('@metadata');
    }

    /**
     * @param $challengeResponses
     * @return \Aws\Result
     */
    public function respondToAuthChallenge( $challengeResponses ) {

        return  $this->cognitoClient->getClient()->respondToAuthChallenge( $challengeResponses );
    }

    /**
     * @param $data
     * @return TokenDTO
     * @throws BadRequestException
     */
    public function respondForceChangePasswordChallenge( $data ) {

        $challengeResponses = [
            'ChallengeName'       => 'NEW_PASSWORD_REQUIRED',
            'ClientId'            => $this->cognitoClient->getClientId(),
            'ChallengeResponses'  => [
                'USERNAME'        => $data['username'],
                'NEW_PASSWORD'    => $data['new_password'],
                'SECRET_HASH'     => $this->cognitoSecretHash($data['username']),
                'userAttributes.given_name' => $data['first_name'],
            ],
            'Session'             => $data['session_value'],
        ];

        try {
            $result = $this->respondToAuthChallenge( $challengeResponses );

            $authResult = $result->get('AuthenticationResult');

            if( $authResult ) {
                return (new TokenDTO())
                    ->accessToken( $authResult['AccessToken'] )
                    ->expiresIn( $authResult['ExpiresIn'] )
                    ->idToken( $authResult['IdToken'] )
                    ->refreshToken( $authResult['RefreshToken'] )
                    ;
            }

        }
        catch (CognitoIdentityProviderException $e) {
            Log::error( $e );
            throw new BadRequestException( $e->getAwsErrorMessage() );
        }

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
    public function listUsers(
        array $attributes = [],
        string $filter = '',
        int $limit = 60,
        int|string $pagination = ''
    ): array
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

    /**
     * @param $token
     * @return mixed|null
     */
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
