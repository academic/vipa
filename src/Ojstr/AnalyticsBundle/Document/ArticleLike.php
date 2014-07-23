<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collections keeps page information and like count *without user info*
 * @MongoDb\Document(collection="analytics_views_article") 
 */
class ArticleLike extends ArticleView {

    /**
     * @MongoDb\Int 
     */
    protected $total;

    /**
     * Page full url with domain
     * @MongoDb\String 
     * @MongoDb\Index() 
     */
    protected $pageUrl;

    /**
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

    /**
     * Set total
     *
     * @param integer $total
     * @return self
     */
    public function setTotal($total) {
        $this->total = $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return integer $total
     */
    public function getTotal() {
        return $this->pagetotal;
    }

}
