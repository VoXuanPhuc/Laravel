<?php

namespace Encoda\AWSCognito\DTO;

use Encoda\AWSCognito\Enums\AllowedOAuthFlowEnums;
use Encoda\AWSCognito\Enums\AuthFlowEnums;

class UserPoolClient
{

    public string $clientId;
    public string $clientName;
    public string $userPoolId;
    public int $accessTokenValidity = 1; //HOURS
    public int $idTokenValidity = 1; //HOURS
    public int $refreshTokenValidity = 1; //HOURS
    public bool $userDataShared = false;
    public string $applicationId = '';
    public bool $allowOAuthFlowUserPoolClient = true;

    public array $callbackURLs = [];
    public array $logoutURLs = [];
    public array $allowedOAuthFlows = [
        AllowedOAuthFlowEnums::IMPLICIT,
    ];

    public array $explicitAuthFlows = [
        AuthFlowEnums::ALLOW_USER_PASSWORD_AUTH,
        AuthFlowEnums::ALLOW_REFRESH_TOKEN_AUTH,
    ];

    public array $allowedOAuthScopes = [
        'email',
        'openid',
    ];
    public array $writeAttributes = [
        'address',
        'birthdate',
        'custom:client_id',
        'email',
        'family_name',
        'gender',
        'given_name',
        'locale',
        'middle_name',
        'name',
        'nickname',
        'phone_number',
        'picture',
        'preferred_username',
        'profile',
        'website',
        'zoneinfo',


    ];

    public array $readAttributes = [
        'address',
        'birthdate',
        'custom:client_id',
        'email',
        'email_verified',
        'family_name',
        'gender',
        'given_name',
        'locale',
        'middle_name',
        'name',
        'nickname',
        'phone_number',
        'phone_number_verified',
        'picture',
        'preferred_username',
        'profile',
        'updated_at',
        'website',
        'zoneinfo',

    ];
    public $defaultRedirectURI;


    public function __construct( $clientName, $userPoolId = '' )
    {
        $this->clientName = $clientName;
        $this->userPoolId = $userPoolId;
    }

    /**
     * @return array
     */
    public function toArray() {

        return [
            'AccessTokenValidity' => $this->accessTokenValidity,
            'AllowedOAuthFlows' => $this->allowedOAuthFlows,
            'AllowedOAuthFlowsUserPoolClient' => $this->allowOAuthFlowUserPoolClient,
            'AllowedOAuthScopes' => $this->allowedOAuthScopes,
            'CallbackURLs' => $this->callbackURLs,
            'ClientName' => $this->clientName, // REQUIRED
            'DefaultRedirectURI' => $this->defaultRedirectURI,
            'EnablePropagateAdditionalUserContextData' => true,
            'EnableTokenRevocation' => true,
            'ExplicitAuthFlows' => $this->explicitAuthFlows,
            'GenerateSecret' => true,
            'IdTokenValidity' => $this->idTokenValidity,
            'LogoutURLs' => $this->logoutURLs,
            'PreventUserExistenceErrors' => 'ENABLED',

            'RefreshTokenValidity' => $this->refreshTokenValidity,
            'SupportedIdentityProviders' => [],
            'TokenValidityUnits' => [
                'AccessToken' => 'days',
                'IdToken' => 'days',
                'RefreshToken' => 'days',
            ],
            'UserPoolId' => $this->userPoolId,
            'WriteAttributes' => $this->writeAttributes,
            'ReadAttributes' => $this->readAttributes,
        ];
    }

    /**
     * @return string
     */
    protected function generateClientArn(): string
    {

        $arnIdentifiers = [
            'cognito',
            $this->userPoolId,
            time(),
            'app-client-' . $this->sanitizeClientName( $this->clientName ),

        ];
        return strtolower( 'arn:' . implode( ':', $arnIdentifiers ) );
    }


    /**
     * @param $clientName
     * @return string
     */
    public function sanitizeClientName( $clientName ) {
        return strtolower( preg_replace(  "/[^A-Za-z0-9]/", '-', $clientName ) );
    }

}
