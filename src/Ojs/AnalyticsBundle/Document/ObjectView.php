<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps object information and total count *view action details*
 * There will be one record for *each object*
 * @MongoDb\Document(collection="analytics_view_object_sum")
 */
class ObjectView
{
    /**
     * @MongoDb\Id
     */
    public $id;

    /**
     * @MongoDb\String
     */
    protected $pageUrl;

    /**
     * @MongoDb\String
     */
    protected $total;

    /** @MongoDB\Int */
    protected $objectId;

    /** @MongoDB\String */
    protected $entity;

    /** @MongoDB\String */
    protected $rawData;

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
     * Page full url with domain
     * Set total
     *
     * @param  string $total
     * @return self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string $total
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * Page full url with domain
     * Set pageUrl
     *
     * @param  string $pageUrl
     * @return self
     */
    public function setPageUrl($pageUrl)
    {
        $this->pageUrl = $pageUrl;

        return $this;
    }

    /**
     * Get pageUrl
     *
     * @return string $pageUrl
     */
    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    /**
     * @param int $id
     * @return $this
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
     * @return $this
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


    public function getRawData()
    {
        return $this->rawData;
    }

    public function setRawData($data)
    {
        $this->rawData = $data;
        return $this;
    }
}
