<?php

namespace Encoda\Identity\Listeners;

use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\Identity\Models\Database\User;
use Illuminate\Support\Facades\Hash;

class CreateCognitoUser
{


    /**
     * @param CognitoUser $cognitoUser
     */
    public function handle( $cognitoUser ) {


        if( !$cognitoUser->getLinkedUser() ) {

            $user = new User([

                'uid' => $cognitoUser->id,
                'first_name' => $cognitoUser->firstName,
                'last_name' => $cognitoUser->lastName,
                'email' => $cognitoUser->username,
                'password' => Hash::make( $cognitoUser->password ),
            ]);

            $user->save();
        }

    }

    /**
     * @return string
     */
    protected function tempPassword() {
        return \Illuminate\Support\Str::random(8);
    }
}
