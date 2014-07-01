<?php

namespace Ojstr\UserBundle\Entity;

 
/**
 * EventLog
 */
class EventLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $eventInfo;

    /**
     * @var \DateTime
     */
    private $eventDate;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var integer
     */
    private $userId;


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
     * Set eventInfo
     *
     * @param string $eventInfo
     * @return EventLog
     */
    public function setEventInfo($eventInfo)
    {
        $this->eventInfo = $eventInfo;

        return $this;
    }

    /**
     * Get eventInfo
     *
     * @return string 
     */
    public function getEventInfo()
    {
        return $this->eventInfo;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     * @return EventLog
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime 
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return EventLog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return EventLog
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
