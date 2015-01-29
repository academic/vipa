<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="review_forms",repositoryClass="Ojs\WorkflowBundle\Repository\ReviewFormRepository")
 */
class ReviewForm
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /** @MongoDb\Int */
    protected $journalId;

    /** @MongoDb\String */
    protected $title;

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
     * Set journalId
     *
     * @param int $journalId
     * @return self
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return int $journalId
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

}
