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

    /** @MongoDb\EmbedOne(targetDocument="JournalWorkflowStep") */
    protected $step;

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
    protected $articleId;

    /**
     * article reviewed data
     * @MongoDB\Hash
     */
    private $articleRevised;

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
    protected $statusText;

    /**
     * @MongoDB\Date
     */
    protected $startedDate;

    /**
     * @MongoDB\Date
     */
    protected $finishedDate;

    /**
     * @MongoDB\Date
     */
    protected $reviewDeadline;

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
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return int $articleId
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set articleRevised
     *
     * @param  hash $articleRevised
     * @return self
     */
    public function setArticleRevised($articleRevised)
    {
        $this->articleRevised = $articleRevised;

        return $this;
    }

    /**
     * Get articleRevised
     *
     * @return hash $articleRevised
     */
    public function getArticleRevised()
    {
        return $this->articleRevised;
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
     * Get step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set step
     */
    public function setStep($step)
    {
        $this->step = $step;
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
        $this->statusText = $statusText;

        return $this;
    }

    /**
     * Get statusText
     *
     * @return string $statusText
     */
    public function getStatusText()
    {
        return $this->statusText;
    }

    /**
     * Set startedDate
     *
     * @param  date $startedDate
     * @return self
     */
    public function setStartedDate($startedDate)
    {
        $this->startedDate = $startedDate;

        return $this;
    }

    /**
     * Get startedDate
     *
     * @return date $startedDate
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * Set finishedDate
     *
     * @param  date $finishedDate
     * @return self
     */
    public function setFinishedDate($finishedDate)
    {
        $this->finishedDate = $finishedDate;

        return $this;
    }

    /**
     * Get finishedDate
     *
     * @return date $finishedDate
     */
    public function getFinishedDate()
    {
        return $this->finishedDate;
    }

    /**
     * Set reviewDeadline
     *
     * @param  date $reviewDeadline
     * @return self
     */
    public function setReviewDeadline($reviewDeadline)
    {
        $this->reviewDeadline = $reviewDeadline;

        return $this;
    }

    /**
     * Get reviewDeadline
     *
     * @return date $reviewDeadline
     */
    public function getReviewDeadline()
    {
        return $this->reviewDeadline;
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
     * @param  Ojs\UserBundle\User $user
     * @return self
     */
    public function setOwnerUser($user)
    {
        $this->ownerUser = array('id' => $user->getId(), 'username' => $user->getUsername(), 'email' => $user->getEmail());
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
