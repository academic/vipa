<?php

namespace Ojs\JournalBundle\Entity;

/**
 * ArticleEventLog
 */
class ArticleEventLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $eventInfo;

    /**
     * @var \DateTime
     */
    private $eventDate;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var integer
     */
    private $articleId;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventInfo
     *
     * @param  string   $eventInfo
     * @return ArticleEventLog
     */
    public function setEventInfo($eventInfo)
    {
        $this->eventInfo = $eventInfo;

        return $this;
    }

    /**
     * Get eventInfo
     *
     * @return string
     */
    public function getEventInfo()
    {
        return $this->eventInfo;
    }

    /**
     * Set eventDate
     *
     * @param  \DateTime $eventDate
     * @return ArticleEventLog
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set ip
     *
     * @param  string   $ip
     * @return ArticleEventLog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set articleId
     *
     * @param  integer  $articleId
     * @return ArticleEventLog
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer
     */
    public function getArticleId()
    {
        return $this->articleId;
    }
}
