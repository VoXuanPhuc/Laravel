<?php
namespace Encoda\AWSCognito\Client;


use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AWSCognitoClient
{

    const NEW_PASSWORD_CHALLENGE = 'NEW_PASSWORD_REQUIRED';
    const FORCE_PASSWORD_STATUS  = 'FORCE_CHANGE_PASSWORD';
    const RESET_REQUIRED         = 'PasswordResetRequiredException';
    const USER_NOT_FOUND         = 'UserNotFoundException';
    const USERNAME_EXISTS        = 'UsernameExistsException';
    const INVALID_PASSWORD       = 'InvalidPasswordException';
    const CODE_MISMATCH          = 'CodeMismatchException';
    const EXPIRED_CODE           = 'ExpiredCodeException';

    /**
     * @var CognitoIdentityProviderClient
     */
    private CognitoIdentityProviderClient $client;

    /**
     * @var
     */
    private $clientId;

    /**
     * @var
     */
    private $clientSecret;

    /**
     * @var
     */
    private $poolId;

    public function __construct(CognitoIdentityProviderClient $client, $clientId, $clientSecret, $poolId )
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->poolId = $poolId;
    }

    /**
     * @param $username
     * @param $password
     * @return \Aws\Result|false
     */
    public function authenticate( $username, $password ) {

        try {
            $response = $this->client->adminInitiateAuth(
                [
                    'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                    'AuthParameters' => [
                        'USERNAME'     => $username,
                        'PASSWORD'     => $password,
                        'SECRET_HASH'  => $this->cognitoSecretHash($username)
                    ],
                    'ClientId'   => $this->clientId,
                    'UserPoolId' => $this->poolId
                ]
            );
        }
        catch ( CognitoIdentityProviderException $exception ) {

            if ($exception->getAwsErrorCode() === self::RESET_REQUIRED ||
                $exception->getAwsErrorCode() === self::USER_NOT_FOUND) {
                return false;
            }

            throw $exception;
        }

        return $response;
    }

    /**
     * @param $username
     * @param $password
     * @param array $attributes
     * @return \Aws\Result|false
     */
    public function register( $username, $password, $attributes = [] ) {

        try {
            $response = $this->client->signUp(
                [
                    'ClientId' => $this->clientId,
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

        return $response;
    }

    public function sendResetLink( $username ) {
        try {

            $result = $this->client->forgotPassword([
                'ClientId' => $this->clientId,
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


    /** ======= HELPERS ======= */
    /**
     * @param $username
     * @param array $attributes
     * @return bool
     */
    public function setUserAttributes($username, array $attributes)
    {
        $this->client->AdminUpdateUserAttributes([
            'Username' => $username,
            'UserPoolId' => $this->poolId,
            'UserAttributes' => $this->formatAttributes($attributes),
        ]);

        return true;
    }

    /**
     * Format attributes in Name/Value array
     * @param array $attributes
     * @return array
     */
    protected function formatAttributes(array $attributes)
    {
        $userAttributes = [];

        foreach ($attributes as $key => $value) {
            $userAttributes[] = [
                'Name' => $key,
                'Value' => $value,
            ];
        }

        return $userAttributes;
    }

    /**
     * @param $username
     * @return string
     */
    protected function cognitoSecretHash($username)
    {
        return $this->hash( $username . $this->clientId );
    }

    /**
     * @param $message
     * @return string
     */
    protected function hash($message)
    {
        $hash = hash_hmac(
            'sha256',
            $message,
            $this->clientSecret,
            true
        );

        return base64_encode($hash);
    }
}
