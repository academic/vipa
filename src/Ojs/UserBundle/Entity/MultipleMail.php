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
    private $activation_code;

    /**
     * @var boolean
     */
    private $is_confirmed;

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
     * @return MultipleMail
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
     * @return MultipleMail
     */
    public function setActivationCode($activationCode)
    {
        $this->activation_code = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return MultipleMail
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->is_confirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return boolean
     */
    public function getIsConfirmed()
    {
        return $this->is_confirmed;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return MultipleMail
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
     * @param \Ojs\UserBundle\Entity\User $user
     *
     * @return MultipleMail
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
