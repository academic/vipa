<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps page information and view action details *without total count*
 * There will be one record *foreach action*
 * @MongoDb\Document(collection="analytics_views_object",repositoryClass="Ojs\AnalyticsBundle\Document\ObjectViewsRepository")
 */
class ObjectViews extends ObjectStatsBase
{
    /**
     * @MongoDb\String
     */
    protected $pageUrl;

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
}
