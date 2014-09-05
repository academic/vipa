<?php

namespace Ojstr\UserBundle\Entity;

/**
 * Proxy
 */
class Proxy extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $proxyUserId;

    /**
     * @var integer
     */
    private $targetUserId;

    /**
     * @var User
     */
    private $proxyUser;

    /**
     * @var User
     */
    private $targetUser;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set proxyUserId
     *
     * @param integer $proxyUserId
     * @return Proxy
     */
    public function setProxyUserId($proxyUserId) {
        $this->proxyUserId = $proxyUserId;

        return $this;
    }

    /**
     * Get proxyUserId
     *
     * @return integer 
     */
    public function getProxyUserId() {
        return $this->proxyUserId;
    }

    /**
     * Set targetUserId
     *
     * @param integer $targetUserId
     * @return Proxy
     */
    public function setTargetUserId($targetUserId) {
        $this->targetUserId = $targetUserId;

        return $this;
    }

    /**
     * Get targetUserId
     *
     * @return integer 
     */
    public function getTargetUserId() {
        return $this->targetUserId;
    }

    /**
     * Set targetUser
     *
     * @param User $user
     * @return Proxy
     */
    public function setTargetUser($user) {
        $this->targetUser = $user;
        return $this;
    }

    /**
     * Get targetUser
     *
     * @return User 
     */
    public function getTargetUser() {
        return $this->targetUser;
    }

    /**
     * Set proxyUser
     *
     * @param User $user
     * @return Proxy
     */
    public function setProxyUser($user) {
        $this->proxyUser = $user;
        return $this;
    }

    /**
     * Get proxyUser
     *
     * @return User 
     */
    public function getProxyUser() {
        return $this->proxyUser;
    }

}
