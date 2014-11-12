<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

class ObjectStatsBase
{
    /**
     * @MongoDb\Id
     */
    public $id;

    /**
     * @MongoDb\Int @MongoDb\Index()
     * @var int $objectId
     */
    public $objectId;

    /** @MongoDb\String */
    public $entity;

    /** @MongoDb\String */
    public $ipAddress;

    /** @MongoDb\String */
    public $referer;

    /**
     * @MongoDB\Date
     */
    public $logDate;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ipAddress
     *
     * @param  string $ipAddress
     * @return self
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string $ipAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set referer
     *
     * @param  string $referer
     * @return self
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return string $referer
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set logDate
     *
     * @param  \Datetime $logDate
     * @return self
     */
    public function setLogDate($logDate)
    {
        $this->logDate = $logDate;

        return $this;
    }

    /**
     * Get logDate
     *
     * @return date $logDate
     */
    public function getLogDate()
    {
        return $this->logDate;
    }

    /**
     * @param int $id
     * @return ObjectStatsBase $this
     */
    public function setObjectId($id)
    {
        $this->objectId = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $entity
     * @return ObjectStatsBase $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

}
