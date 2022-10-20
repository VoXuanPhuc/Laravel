<?php

namespace Encoda\AWSCognito\Services;

use Encoda\AWSCognito\Entities\JwtToken;
use Encoda\AWSCognito\Exceptions\InvalidTokenException;
use Encoda\AWSCognito\Exceptions\InvalidTokenStructureException;
use Encoda\AWSCognito\Exceptions\TokenBlackListedException;
use Encoda\AWSCognito\Factories\CognitoAuthFactory;
use Encoda\AWSCognito\Providers\TokenBlacklistProvider;
use Illuminate\Support\Collection;
use Laravel\Lumen\Application;

class CognitoJWTManager
{

    protected bool $checkBlackListEnabled = true;
    protected TokenBlacklistProvider $blackList;
    protected CognitoTokenParser $parser;
    protected CognitoJWTVerifier $verifier;

    public function __construct()
    {
        $this->blackList = app('cognito.blacklist');
        $this->parser = app('cognito.parser');
        $this->verifier = app('cognito.verifier');
    }

    /**
     * @param $jwt
     * @return array
     * @throws TokenBlackListedException|InvalidTokenStructureException
     * @throws InvalidTokenException
     */
    public function decode( $jwt ): array
    {

        $token = $this->parseToken( $jwt );

        if( $this->checkBlackListEnabled && $this->blackList->has( $token ) ) {
            throw new TokenBlackListedException();
        }

        $this->verifier->verify( $token );

        return Collection::wrap( $token->claims() )->toArray();
    }

    /**
     * Splits the JWT string into an array
     *
     * @return string[]
     *
     * @throws InvalidTokenStructureException When JWT doesn't have all parts.
     */
    private function splitJwt(string $jwt): array
    {
        $data = explode('.', $jwt);

        if ( count($data) !== 3 ) {
            throw new InvalidTokenStructureException();
        }

        return $data;
    }

    /**
     * @throws InvalidTokenStructureException
     */
    public function parseToken( $jwt ) {

        [$encodedHeaders, $encodedClaims, $encodedSignature] = $this->splitJwt( $jwt );

        $header = $this->parser->parseHeader( $encodedHeaders );
        $claims = $this->parser->parseClaims( $encodedClaims );
        $signature = $this->parser->parseSignature( $header, $encodedSignature );

        return new JwtToken(
            $jwt,
            $header,
            $claims,
            $signature
        );
    }

    /**
     * @return CognitoAuthFactory
     */
    public function getAuthFactory(): CognitoAuthFactory
    {
        return app( CognitoAuthFactory::class );
    }


}
