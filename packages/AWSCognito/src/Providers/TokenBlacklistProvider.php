<?php

namespace Encoda\AWSCognito\Providers;

class TokenBlacklistProvider
{

    public function has( $payload ) {
        return false;
    }
}
