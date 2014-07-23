<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDb\Document(collection="analytics_views_article") 
 */
class ArticleDownload extends ArticleStatsBase {

    /**
     * @MongoDb\String
     */
    protected $filePath;

    /**
     * Optional 
     * @MongoDb\Int  
     */
    protected $transferSize;

    /**
     * Set transferSize
     *
     * @param int $transferSize
     * @return self
     */
    public function setTransferSize($transferSize) {
        $this->transferSize = $transferSize;
        return $this;
    }

    /**
     * Get transferSize
     *
     * @return int $transferSize
     */
    public function getTransferSize() {
        return $this->transferSize;
    }

    /**
     * Page full url with domain
     * Set filePath
     *
     * @param string $filePath
     * @return self
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * Get filePath
     *
     * @return string $filePath
     */
    public function getFilePath() {
        return $this->filePath;
    }

}
