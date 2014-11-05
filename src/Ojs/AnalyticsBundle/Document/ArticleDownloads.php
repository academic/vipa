<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps page information and download action details *without total count*
 * There will be one record *foreach action*
 * @MongoDb\Document(collection="analytics_downloads_article")
 */
class ArticleDownloads extends ArticleStatsBase
{
    /**
     * @MongoDb\Id
     */
    public $id;

    /**
     * @MongoDb\String
     */
    protected $filePath;

    /**
     * @MongoDb\String
     */
    protected $total;

    /**
     * Optional
     * @MongoDb\Int
     */
    protected $transferSize;

    /**
     * Set transferSize
     *
     * @param  int  $transferSize
     * @return self
     */
    public function setTransferSize($transferSize)
    {
        $this->transferSize = $transferSize;

        return $this;
    }

    /**
     * Get transferSize
     *
     * @return int $transferSize
     */
    public function getTransferSize()
    {
        return $this->transferSize;
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

}
