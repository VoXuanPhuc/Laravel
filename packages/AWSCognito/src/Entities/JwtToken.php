<?php

namespace Encoda\AWSCognito\Entities;

class JwtToken
{

    protected $jwt;
    protected $header;
    protected $claims;
    protected $signature;

    public function __construct( $jwt, $header, $claims, $signature )
    {
        $this->jwt = $jwt;
        $this->header = $header;
        $this->claims = $claims;
        $this->signature = $signature;
    }

    public function header(){
        return $this->header;
    }

    /**
     * @return mixed
     */
    public function claims(){
        return $this->claims;
    }

    /**
     * @return mixed
     */
    public function signature() {
        return $this->signature;

    }

    /**
     * @return mixed
     */
    public function value() {
        return $this->jwt;
    }
}
