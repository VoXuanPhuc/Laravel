<?php

namespace Encoda\AWSCognito\Models;

use Encoda\Identity\Models\Database\User;
use Illuminate\Support\Facades\Hash;

class CognitoUser extends CognitoBaseModel
{

    public $id;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
    public $clientId;
    public $isEmailConfirmed;
    public $createdAt;
    public $status;
    public $isActive;
    public $token;
    public $remember_token;

    /**
     * @var User $linkedUser
     */
    protected User $linkedUser;

    protected array $ignores = [
        'linkedUser'
    ];


    /**
     * @return mixed
     */
    public function getRole(): mixed
    {

        $linkedUserRole = $this->getLinkedUser()->roles->first();
        return $linkedUserRole?->toArray();
    }

    /**s
     * @param $cognitoUser
     * @param string $attributeKey
     * @return CognitoUser
     */
    public static function transformFromAdminCreateUser($cognitoUser, string $attributeKey = 'Attributes' ): CognitoUser
    {

        $attributes = [];
        foreach ( $cognitoUser[$attributeKey]  as $attribute ) {
            $attributes[$attribute['Name']] = $attribute['Value'];
        }

        $user = new CognitoUser();

        $user->id = $cognitoUser['Username'];
        $user->username = $attributes['email'];
        $user->firstName = $attributes['given_name'];
        $user->lastName = $attributes['family_name'];
        $user->clientId = $attributes['custom:client_id'];
        $user->isEmailConfirmed = $cognitoUser['Enabled'];
        $user->createdAt = $cognitoUser['UserCreateDate']->format('c');
        $user->status = $cognitoUser['UserStatus'];

        return $user;
    }

    /**
     * @param $cognitoUser
     * @param string $attributeKey
     * @return CognitoUser
     */
    public static function transformUser($cognitoUser, string $attributeKey = 'UserAttributes' ): CognitoUser
    {

        $userAttributes = [];
        foreach ( $cognitoUser[$attributeKey]  as $attribute ) {
            $userAttributes[$attribute['Name']] = $attribute['Value'];
        }

        $user = new CognitoUser();

        $user->id = $cognitoUser->get('Username');
        $user->username = $userAttributes['email'];
        $user->firstName = $userAttributes['given_name'];
        $user->lastName = $userAttributes['family_name'];
        $user->clientId = $userAttributes['custom:client_id'];
        $user->isEmailConfirmed = $userAttributes['email_verified'];
        $user->createdAt = $cognitoUser->get('UserCreateDate')->format('c');
        $user->isActive = $cognitoUser->get('Enabled');
        $user->status = $cognitoUser->get('UserStatus');

        return $user;
    }


    /**
     * @param $cognitoUser
     * @param string $attributeKey
     * @return CognitoUser
     */
    public static function transformUserFromList($cognitoUser, string $attributeKey = 'Attributes' ): CognitoUser
    {

        $attributes = [];

        foreach ( $cognitoUser[$attributeKey]  as $attribute ) {
            $attributes[$attribute['Name']] = $attribute['Value'];
        }

        $user = new CognitoUser();

        $user->id = $cognitoUser['Username'];
        $user->username = $attributes['email'];
        $user->firstName = $attributes['given_name'];
        $user->lastName = $attributes['family_name'];
        $user->clientId = $attributes['custom:client_id'];
        $user->isEmailConfirmed = $attributes['email_verified'];
        $user->isActive = $cognitoUser['Enabled'];
        $user->createdAt = $cognitoUser['UserCreateDate'];
        $user->status = $cognitoUser['UserStatus'];

        return $user;
    }

    /**
     * @return mixed
     */
    public function getLinkedUser(): mixed
    {
        $user = User::where('uid', $this->id )->first();

        //Fall back to email
        if( !$user ) {
            $user = User::where('email', $this->username )->first();
        }

        if( $user ) {
            $this->linkedUser = $user;
            $this->linkedUser->uid = $this->id;
            $this->linkedUser->save();
        }
        else {
            $this->createLinkedUser();
        }


        return $this->linkedUser;
    }

    /**
     * Create linked user
     */
    protected function createLinkedUser() {

        $user = new User([

            'uid' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->username,
            'password' => $this->password ? Hash::make( $this->password ) : '',
        ]);

        $user->save();

        $this->linkedUser = $user;
    }
}
