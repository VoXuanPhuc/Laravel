<?php
namespace Encoda\Auth\Services;

use Encoda\Auth\Http\Requests\SignupRequest;
use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\Core\Exceptions\BadRequestException;
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
     * @throws BadRequestException
     */
    public function authenticate(Request $request ) {

        if( empty( request('username') ) || empty( request('password') ) ) {
            throw new BadRequestException('Username or password incorrect');
        }
        return $this->awsCognitoClient->authenticate( request('username'),request('password') );
    }

    /**
     * @param SignupRequest $signupRequest
     * @return \Aws\Result|false
     */
    public function signup( SignupRequest $signupRequest ) {

        return $this->awsCognitoClient->register(
            $signupRequest->username,
            $signupRequest->password,
            [
                'email' => $signupRequest->username,
                'given_name' => $signupRequest->firstName,
                'family_name' => $signupRequest->lastName,
            ]
        );
    }
}
