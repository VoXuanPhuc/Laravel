<?php

namespace Encoda\Auth\DTOs;

class AuthChallengeDTO extends BaseDTO
{

    protected string $challengeName;
    protected string $session;
    protected string $userUid;
    protected string $firstName;

    /**
     * @return string
     */
    public function getChallengeName(): string
    {
        return $this->challengeName;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getUserUid(): string
    {
        return $this->userUid;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }


    /**
     * @param mixed $challengeName
     */
    public function challengeName( $challengeName ): static
    {
        $this->challengeName = $challengeName;

        return $this;
    }

    /**
     * @param mixed $session
     */
    public function session( $session ): static
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @param mixed $userUid
     */
    public function userUid( $userUid ): static
    {
        $this->userUid = $userUid;

        return $this;
    }

    /**
     * @param mixed $firstName
     */
    public function firstName( $firstName ): static
    {
        $this->firstName = $firstName;

        return $this;
    }

}
