<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer
     */
    private $user_id;

    /**
     * @var string
     */
    private $provider;

    /**
     * @var string
     */
    private $provider_user_id;

    /**
     * @var string
     */
    private $provider_access_token;

    /**
     * @var string
     */
    private $provider_refresh_token;

    /**
     * @var \Ojs\UserBundle\Entity\User
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
     * Set user_id
     *
     * @param integer $userId
     * @return UserOauthAccount
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set provider
     *
     * @param string $provider
     * @return UserOauthAccount
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
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
     * Set provider_user_id
     *
     * @param string $providerUserId
     * @return UserOauthAccount
     */
    public function setProviderUserId($providerUserId)
    {
        $this->provider_user_id = $providerUserId;

        return $this;
    }

    /**
     * Get provider_user_id
     *
     * @return string 
     */
    public function getProviderUserId()
    {
        return $this->provider_user_id;
    }

    /**
     * Set provider_access_token
     *
     * @param string $providerAccessToken
     * @return UserOauthAccount
     */
    public function setProviderAccessToken($providerAccessToken)
    {
        $this->provider_access_token = $providerAccessToken;

        return $this;
    }

    /**
     * Get provider_access_token
     *
     * @return string 
     */
    public function getProviderAccessToken()
    {
        return $this->provider_access_token;
    }

    /**
     * Set provider_refresh_token
     *
     * @param string $providerRefreshToken
     * @return UserOauthAccount
     */
    public function setProviderRefreshToken($providerRefreshToken)
    {
        $this->provider_refresh_token = $providerRefreshToken;

        return $this;
    }

    /**
     * Get provider_refresh_token
     *
     * @return string 
     */
    public function getProviderRefreshToken()
    {
        return $this->provider_refresh_token;
    }

    /**
     * Set user
     *
     * @param \Ojs\UserBundle\Entity\User $user
     * @return UserOauthAccount
     */
    public function setUser(\Ojs\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojs\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
