<?php
namespace Encoda\Auth\Services;

use Encoda\Auth\Http\Requests\SignupRequest;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class UserService
{

    use ProvidesConvenienceMethods;

    protected $awsCognitoClient;

    public function __construct( AWSCognitoClient $cognitoClient )
    {
        $this->awsCognitoClient = $cognitoClient;
    }

    /**
     * @param Request $request
     * @return \Aws\Result|false
     */
    public function authenticate(Request $request ) {

        return $this->awsCognitoClient->authenticate( $request->get('username'), $request->get('password') );
    }

    /**
     * @param SignupRequest $signupRequest
     * @return \Aws\Result|false
     */
    public function signup( SignupRequest $signupRequest ) {

        return $this->awsCognitoClient->register(
            $signupRequest->email,
            $signupRequest->password,
            [
                'email' => $signupRequest->email,
                'given_name' => $signupRequest->firstName,
                'family_name' => $signupRequest->lastName,
            ]
        );
    }
}
