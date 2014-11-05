<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="article_review_steps")
 */
class ArticleReviewStep
{
    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * Is this is the first node of the article review steps?
     * @MongoDb\Boolean
     */
    protected $rootNode;

    /**
     * owner user     { "id" : ... , "username" : ... , "email" : ... }
     * @MongoDB\Hash
     */
    protected $ownerUser;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $action;

    /** @MongoDb\String */
    protected $note;

    /** @MongoDB\Int */
    protected $article_id;

    /**
     * article reviewed data
     * @MongoDB\Hash
     */
    private $article_revised;

    /**
     * @MongoDB\Hash
     */
    protected $from;

    /**
     * @MongoDB\Hash
     */
    protected $to;

    /**
     * @MongoDB\String
     */
    protected $status_text;

    /**
     * @MongoDB\Date
     */
    protected $started_date;

    /**
     * @MongoDB\Date
     */
    protected $finished_date;

    /**
     * @MongoDB\Date
     */
    protected $review_deadline;

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
     * Set action
     *
     * @param  int  $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return int $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set note
     *
     * @param  string $note
     * @return self
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set articleId
     *
     * @param  int  $articleId
     * @return self
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return int $articleId
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Set articleRevised
     *
     * @param  hash $articleRevised
     * @return self
     */
    public function setArticleRevised($articleRevised)
    {
        $this->article_revised = $articleRevised;

        return $this;
    }

    /**
     * Get articleRevised
     *
     * @return hash $articleRevised
     */
    public function getArticleRevised()
    {
        return $this->article_revised;
    }

    /**
     * Set from
     *
     * @param  hash $from
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return hash $from
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param  hash $to
     * @return self
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return hash $to
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set statusText
     *
     * @param  string $statusText
     * @return self
     */
    public function setStatusText($statusText)
    {
        $this->status_text = $statusText;

        return $this;
    }

    /**
     * Get statusText
     *
     * @return string $statusText
     */
    public function getStatusText()
    {
        return $this->status_text;
    }

    /**
     * Set startedDate
     *
     * @param  date $startedDate
     * @return self
     */
    public function setStartedDate($startedDate)
    {
        $this->started_date = $startedDate;

        return $this;
    }

    /**
     * Get startedDate
     *
     * @return date $startedDate
     */
    public function getStartedDate()
    {
        return $this->started_date;
    }

    /**
     * Set finishedDate
     *
     * @param  date $finishedDate
     * @return self
     */
    public function setFinishedDate($finishedDate)
    {
        $this->finished_date = $finishedDate;

        return $this;
    }

    /**
     * Get finishedDate
     *
     * @return date $finishedDate
     */
    public function getFinishedDate()
    {
        return $this->finished_date;
    }

    /**
     * Set reviewDeadline
     *
     * @param  date $reviewDeadline
     * @return self
     */
    public function setReviewDeadline($reviewDeadline)
    {
        $this->review_deadline = $reviewDeadline;

        return $this;
    }

    /**
     * Get reviewDeadline
     *
     * @return date $reviewDeadline
     */
    public function getReviewDeadline()
    {
        return $this->review_deadline;
    }

    /**
     * Set rootNode
     *
     * @param  boolean $rootNode
     * @return self
     */
    public function setRootNode($rootNode)
    {
        $this->rootNode = $rootNode;

        return $this;
    }

    /**
     * Get rootNode
     *
     * @return boolean $rootNode
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

    /**
     * Set ownerUser
     *
     * @param  hash $ownerUser
     * @return self
     */
    public function setOwnerUser($ownerUser)
    {
        $this->ownerUser = $ownerUser;

        return $this;
    }

    /**
     * Get ownerUser
     *
     * @return hash $ownerUser
     */
    public function getOwnerUser()
    {
        return $this->ownerUser;
    }
}
