<?php

namespace Encoda\Identity\Models\Cognito;

use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Identity\Contracts\UserContract;

class CognitoUser extends CognitoBaseModel implements UserContract
{
    public $token;

    protected CognitoUserService $userService;


    public function __construct(CognitoUserService $userService, array $attributes = [] )
    {
        $this->userService = $userService;
        parent::__construct($attributes);
    }

    public function list($columns = ['*'])
    {
        return $this->userService->listUsers();
    }

    /**
     * @param $attributes
     */
    public function create( $attributes )
    {
        $cognitoRequiredAttributes['given_name'] = $attributes['firstName'];
        $cognitoRequiredAttributes['family_name'] = $attributes['lastName'];

        $this->userService->register(
            $attributes['username'],
            $attributes['password'],
            $cognitoRequiredAttributes
        );
    }

   public function delete()
   {
       $this->userService->delete( $this->token );
   }

    public function find($id)
    {
        $user = $this->userService->adminGetUser( $id );
        return $user->toArray();
    }


}
