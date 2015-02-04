<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomField
 */
class CustomField
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $value;

    /**
     * @var boolean
     */
    private $is_url;

    /**
     * @var integer
     */
    private $user_id;

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
     * Set label
     *
     * @param string $label
     * @return CustomField
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return CustomField
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set is_url
     *
     * @param boolean $isUrl
     * @return CustomField
     */
    public function setIsUrl($isUrl)
    {
        $this->is_url = $isUrl;

        return $this;
    }

    /**
     * Get is_url
     *
     * @return boolean 
     */
    public function getIsUrl()
    {
        return $this->is_url;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return CustomField
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
     * Set user
     *
     * @param \Ojs\UserBundle\Entity\User $user
     * @return CustomField
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
