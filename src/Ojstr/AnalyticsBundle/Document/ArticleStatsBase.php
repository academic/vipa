<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

class ArticleStatsBase {

    /**
     * @MongoDb\Id
     */
    protected $id;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $journalId;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $articleId;

    /** @MongoDb\String */
    protected $ipAddress;

    /** @MongoDb\String */
    protected $referer;

    /**
     * @MongoDB\Date
     */
    protected $logDate;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set journalId
     *
     * @param int $journalId
     * @return self
     */
    public function setJournalId($journalId) {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return int $journalId
     */
    public function getJournalId() {
        return $this->journalId;
    }

    /**
     * Set articleId
     *
     * @param int $articleId
     * @return self
     */
    public function setArticleId($articleId) {
        $this->articleId = $articleId;
        return $this;
    }

    /**
     * Get articleId
     *
     * @return int $articleId
     */
    public function getArticleId() {
        return $this->articleId;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return self
     */
    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string $ipAddress
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * Set referer
     *
     * @param string $referer
     * @return self
     */
    public function setReferer($referer) {
        $this->referer = $referer;
        return $this;
    }

    /**
     * Get referer
     *
     * @return string $referer
     */
    public function getReferer() {
        return $this->referer;
    }

    /**
     * Set logDate
     *
     * @param date $logDate
     * @return self
     */
    public function setLogDate($logDate) {
        $this->logDate = $logDate;
        return $this;
    }

    /**
     * Get logDate
     *
     * @return date $logDate
     */
    public function getLogDate() {
        return $this->logDate;
    }

}
