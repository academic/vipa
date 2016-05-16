<?php

namespace Ojs\UserBundle\Entity;

/**
 * MultipleMail
 */
class MultipleMail
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */
    private $activationCode;

    /**
     * @var boolean
     */
    private $isConfirmed;

    /**
     * @var integer
     */
    private $user_id;

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
     * Set mail
     *
     * @param string $mail
     *
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set activationCode
     *
     * @param string $activationCode
     *
     * @return $this
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return $this
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

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
}
