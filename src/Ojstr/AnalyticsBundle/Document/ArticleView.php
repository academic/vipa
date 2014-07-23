<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps page information and total count *view action details*
 * There will be one record for *each pages*
 * @MongoDb\Document(collection="analytics_viewsum_article") 
 */
class ArticleView {

    /**
     * @MongoDb\String
     */
    protected $pageUrl;

    /**
     * @MongoDb\String
     */
    protected $total;

    /**
     * Page full url with domain
     * Set total
     *
     * @param string $total
     * @return self
     */
    public function setTotal($total) {
        $this->total = $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return string $total
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Page full url with domain
     * Set pageUrl
     *
     * @param string $pageUrl
     * @return self
     */
    public function setPageUrl($pageUrl) {
        $this->pageUrl = $pageUrl;
        return $this;
    }

    /**
     * Get pageUrl
     *
     * @return string $pageUrl
     */
    public function getPageUrl() {
        return $this->pageUrl;
    }

}
