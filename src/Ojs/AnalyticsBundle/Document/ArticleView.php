<?php

namespace Ojs\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collection keeps page information and total count *view action details*
 * There will be one record for *each pages*
 * @MongoDb\Document(collection="analytics_view_article_sum")
 */
class ArticleView
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
     * @MongoDB\Int
     */
    protected $articleId;

    /**
     * @MongoDB\Document
     */
    protected $user;

    /**
     * @MongoDB\String
     */
    protected $remoteIp;

    /**
     * @MongoDB\Date
     */
    protected $date;
    /**
     * @MongoDb\String
     */
    protected $total;

    /**
     * Get id
     *
     * @return id $id
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

    public function setUser($id)
    {
        $this->user = $id;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setRemoteIp($ip)
    {
        $this->remoteIp = $ip;
        return $this;
    }

    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function setArticleId($id)
    {
        $this->articleId = $id;
        return $this;
    }
}
