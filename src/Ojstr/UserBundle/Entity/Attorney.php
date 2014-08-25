<?php

namespace Ojstr\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attorney
 */
class Attorney {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $attorneyUserId;

    /**
     * @var integer
     */
    private $targetUserId;

    /**
     * @var User
     */
    private $attorneyUser;

    /**
     * @var User
     */
    private $targetUser;

    /**
     * @var \DateTime
     */
    private $untilDatetime;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set attorneyUserId
     *
     * @param integer $attorneyUserId
     * @return Attorney
     */
    public function setAttorneyUserId($attorneyUserId) {
        $this->attorneyUserId = $attorneyUserId;

        return $this;
    }

    /**
     * Get attorneyUserId
     *
     * @return integer 
     */
    public function getAttorneyUserId() {
        return $this->attorneyUserId;
    }

    /**
     * Set targetUserId
     *
     * @param integer $targetUserId
     * @return Attorney
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
     * @return Attorney
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
     * Set attorneyUser
     *
     * @param User $user
     * @return Attorney
     */
    public function setAttorneyUser($user) {
        $this->attorneyUser = $user;
        return $this;
    }

    /**
     * Get attorneyUser
     *
     * @return User 
     */
    public function getAttorneyUser() {
        return $this->targetUser;
    }

    /**
     * Set untilDatetime
     *
     * @param \DateTime $untilDatetime
     * @return Attorney
     */
    public function setUntilDatetime($untilDatetime) {
        $this->untilDatetime = $untilDatetime;

        return $this;
    }

    /**
     * Get untilDatetime
     *
     * @return \DateTime 
     */
    public function getUntilDatetime() {
        return $this->untilDatetime;
    }

}
