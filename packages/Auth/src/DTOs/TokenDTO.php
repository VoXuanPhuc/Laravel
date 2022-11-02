<?php

namespace Encoda\Auth\DTOs;

class TokenDTO extends BaseDTO
{

    protected string $type = 'bearer';
    protected string $accessToken;
    protected int $expiresIn;
    protected string $idToken;
    protected string $refreshToken;
    public string $landing;

    /**
     * @param $accessToken
     * @return TokenDTO
     */
    public function accessToken( $accessToken ): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param $expiresIn
     * @return TokenDTO
     */
    public function expiresIn( $expiresIn ): static
    {
        $this->expiresIn = (int)$expiresIn;
        return $this;
    }

    /**
     * @param $idToken
     * @return TokenDTO
     */
    public function idToken( $idToken ): static
    {
        $this->idToken = $idToken;
        return $this;
    }

    /**
     * @param $refreshToken
     * @return TokenDTO
     */
    public function refreshToken( $refreshToken ): static
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getIdToken(): string
    {
        return $this->idToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
