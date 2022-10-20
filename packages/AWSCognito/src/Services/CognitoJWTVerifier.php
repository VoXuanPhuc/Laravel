<?php

namespace Encoda\AWSCognito\Services;

use Encoda\AWSCognito\Entities\JwtToken;
use Encoda\AWSCognito\Exceptions\InvalidJWKException;
use Encoda\AWSCognito\Exceptions\InvalidTokenException;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;

class CognitoJWTVerifier
{

    /**
     * @param JwtToken $token
     * @return stdClass
     * @throws InvalidTokenException
     */
    public function verify( JwtToken $token ): stdClass
    {

        try {
            $data = JWT::decode(
                $token->value(),
                JWK::parseKeySet( $this->getJWKs() )
            );
        }
        catch ( Throwable $e ) {
            Log::error( $e );
            throw new InvalidTokenException();

        }

        return $data;
    }

    /**
     * Get JWKs
     */
    protected function getJWKs() {

        $jwkContent = file_get_contents( base_path('/.jwk') );

        $decodedJwkData = json_decode( $jwkContent, true );

        if( !json_last_error() == JSON_ERROR_NONE ) {
            throw new InvalidJWKException( 'JWK error: ' . json_last_error_msg() );
        }

        if( !isset( $decodedJwkData['keys'] ) ) {
            throw new InvalidJWKException( 'JWK error: Missing keys' );
        }

        return $decodedJwkData;
    }
}
