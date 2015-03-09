<?php
/**
 * Date: 21.01.15
 * Time: 15:34
 */

namespace Ojs\UserBundle\Document;


class AnonymUserToken {

    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var int $user_id
     */
    protected $user_id;

    /**
     * @var string $token
     */
    protected $token;

    /**
     * @var boolean $used
     */
    protected $used;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param int $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return self
     */
    public function setUsed($used)
    {
        $this->used = $used;
        return $this;
    }

    /**
     * Get used
     *
     * @return boolean $used
     */
    public function getUsed()
    {
        return $this->used;
    }
}
