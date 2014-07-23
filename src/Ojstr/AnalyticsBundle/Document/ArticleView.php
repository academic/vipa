<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDb\Document(collection="analytics_views_article") 
 */
class ArticleView extends ArticleStatsBase {

    /**
     * @MongoDb\String
     */
    protected $pageUrl;

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
