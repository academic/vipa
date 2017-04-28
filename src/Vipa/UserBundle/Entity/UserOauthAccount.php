<?php

namespace Vipa\UserBundle\Entity;


/**
 * UserOauthAccount
 */
class UserOauthAccount
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var string
     */
    private $providerId;

    /**
     * @var string
     */
    private $token;

    /**
     * @var User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set provider
     *
     * @param  string $provider
     * @return UserOauthAccount
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get providerId
     *
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * Set providerId
     *
     * @param  string $providerId
     * @return UserOauthAccount
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param  string $token
     * @return UserOauthAccount
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User $user
     * @return UserOauthAccount
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
