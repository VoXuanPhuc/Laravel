<?php

namespace Encoda\AWSCognito\Services;

use Encoda\AWSCognito\Exceptions\InvalidTokenStructureException;

class CognitoTokenParser
{

    /**
     * @param string $encodedHeaders
     * @return mixed
     * @throws InvalidTokenStructureException
     */
    public function parseHeader(string $encodedHeaders): mixed
    {
        $header = json_decode( base64_decode( $encodedHeaders ), true );

        if( !is_array( $header ) ) {
            throw new InvalidTokenStructureException('Invalid token header' );

        }

        if (! array_key_exists('typ', $header)) {
            $header['typ'] = 'JWT';
        }

        return $header;
    }

    /**
     * @param string $encodedClaims
     * @return array|mixed
     * @throws InvalidTokenStructureException
     */
    public function parseClaims( string $encodedClaims ) {

        $claims = json_decode( base64_decode( $encodedClaims ), true );

        if( !is_array( $claims ) ) {
            throw new InvalidTokenStructureException('Invalid token claims' );
        }

        return $claims;
    }

    /**
     * Returns the signature from given data
     *
     * @param mixed[] $header
     */
    public function parseSignature(array $header, string $data )
    {
        //TODO: Buid signature check, this is a place holder

        if ($data === '' || ! array_key_exists('alg', $header) || $header['alg'] === 'none') {
            return '';
        }

        $hash = base64_decode($data);

        return [$hash, $data ];
    }
}
