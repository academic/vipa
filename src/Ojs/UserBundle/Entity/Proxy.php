<?php

namespace Ojs\UserBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Proxy
 * @ExclusionPolicy("all")
 */
class Proxy extends \Ojs\Common\Entity\GenericExtendedEntity
{
    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var \DateTime
     * @Expose
     */
    private $ttl;

    /**
     * @var integer
     * @Expose
     */
    private $proxyUserId;

    /**
     * @var integer
     * @Expose
     */
    private $clientUserId;

    /**
     * @var User
     * @Expose
     */
    private $proxyUser;

    /**
     * @var User
     * @Expose
     */
    private $clientUser;

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
     *
     * @return \DateTime
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param  \DateTime $ttl
     * @return Proxy
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Set proxyUserId
     *
     * @param  integer $proxyUserId
     * @return Proxy
     */
    public function setProxyUserId($proxyUserId)
    {
        $this->proxyUserId = $proxyUserId;

        return $this;
    }

    /**
     * Get proxyUserId
     *
     * @return integer
     */
    public function getProxyUserId()
    {
        return $this->proxyUserId;
    }

    /**
     * Set clientUserId
     *
     * @param  integer $clientUserId
     * @return Proxy
     */
    public function setClientUserId($clientUserId)
    {
        $this->clientUserId = $clientUserId;

        return $this;
    }

    /**
     * Get clientUserId
     *
     * @return integer
     */
    public function getClientUserId()
    {
        return $this->clientUserId;
    }

    /**
     * Set clientUser
     *
     * @param  User  $user
     * @return Proxy
     */
    public function setClientUser($user)
    {
        $this->clientUser = $user;

        return $this;
    }

    /**
     * Get clientUser
     *
     * @return User
     */
    public function getClientUser()
    {
        return $this->clientUser;
    }

    /**
     * Set proxyUser
     *
     * @param  User  $user
     * @return Proxy
     */
    public function setProxyUser($user)
    {
        $this->proxyUser = $user;

        return $this;
    }

    /**
     * Get proxyUser
     *
     * @return User
     */
    public function getProxyUser()
    {
        return $this->proxyUser;
    }

}
