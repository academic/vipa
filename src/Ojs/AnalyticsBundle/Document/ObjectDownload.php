<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps page information and download action details *without total count*
 * There will be one record for each paths
 * @MongoDb\Document(collection="analytics_download_object_sum")
 */
class ObjectDownload
{
    /**
     * @MongoDb\Id
     */
    public $id;

    /**
     * @MongoDb\String
     */
    protected $filePath;

    /** @MongoDB\Int */
    protected $objectId;

    /** @MongoDB\string */
    protected $entity;

    /** @MongoDB\String */
    protected $rawData;

    /** @MongoDB\Int */
    protected $total;

    /**
     * Get id
     *
     * @return int $id
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
     * Set filePath
     *
     * @param  string $filePath
     * @return self
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string $filePath
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setObjectId($id)
    {
        $this->objectId = $id;
        return $this;
    }

    public function getObjectId()
    {
        return $this->objectId;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setRawData($data)
    {
        $this->rawData = $data;
        return $this;
    }
/** Jungle Boogie  */
    public function getRawData()
    {
        return $this->rawData;
    }
}
