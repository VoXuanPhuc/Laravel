<?php

namespace Encoda\AWSCognito\Factories;

class CognitoAuthFactory
{

    protected int $ttl = 30;

    /**
     * @param $ttl
     * @return $this
     */
    public function setTTL($ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @return int
     */
    public function getTTL(): int
    {
        return $this->ttl;
    }
}
