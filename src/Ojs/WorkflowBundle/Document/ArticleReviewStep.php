<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * @MongoDb\Document(collection="article_review_steps")
 */
class ArticleReviewStep {

    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * @MongoDb\String
     * article primary language
     */
    protected $primaryLanguage;

    /** @MongoDb\String */
    protected $competingOfInterest;

    /**
     * @MongoDb\ReferenceOne(targetDocument="JournalWorkflowStep",nullable=true)
     */
    protected $step;

    /**
     * Is this is the first node of the article review steps?
     * @MongoDb\Boolean
     */
    protected $rootNode;

    /**
     * @MongoDb\ReferenceOne(targetDocument="ArticleReviewStep",nullable=true)
     *  If this is a child step (invited steps are "child steps") this relation must be set.
     */
    protected $parentStep;

    /**
     * owner user     { "id" : ... , "username" : ... , "email" : ... }
     * @MongoDB\Hash
     */
    protected $ownerUser;

    /**
     * @MongoDb\ReferenceMany(targetDocument="Invitation",nullable=true)
     */
    protected $invitations;

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
     * @MongoDb\ReferenceOne(targetDocument="ArticleReviewStep",nullable=true)
     */
    protected $from;

    /**
     * @MongoDb\ReferenceOne(targetDocument="ArticleReviewStep",nullable=true)
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
     * A short string or code like "rejected" "accepted"
     * @MongoDB\String
     */
    protected $reviewResult;

    /**
     * Long text contains review notes
     * @MongoDB\String
     */
    protected $reviewNotes;

    /**
     * Long text contains review form results
     * @MongoDB\String
     */
    protected $reviewFormResults;

    /**
     * At first step submitterId and ownerUser.id may be equal
     * @MongoDb\Int
     */
    protected $submitterId;

    /**
     * Document Id
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
        $this->ownerUser = !$user ? null : array('id' => $user->getId(), 'username' => $user->getUsername(), 'email' => $user->getEmail());
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

    /**
     * Set reviewResult
     *
     * @param string $reviewResult
     * @return self
     */
    public function setReviewResult($reviewResult)
    {
        $this->reviewResult = $reviewResult;
        return $this;
    }

    /**
     * Get reviewResult
     *
     * @return string $reviewResult
     */
    public function getReviewResult()
    {
        return $this->reviewResult;
    }

    /**
     * Set reviewNotes
     *
     * @param string $reviewNotes
     * @return self
     */
    public function setReviewNotes($reviewNotes)
    {
        $this->reviewNotes = $reviewNotes;
        return $this;
    }

    /**
     * Get reviewNotes
     *
     * @return string $reviewNotes
     */
    public function getReviewNotes()
    {
        return $this->reviewNotes;
    }

    /**
     * Set submitterId
     *
     * @param int $submitterId
     * @return self
     */
    public function setSubmitterId($submitterId)
    {
        $this->submitterId = $submitterId;
        return $this;
    }

    /**
     * Get submitterId
     *
     * @return int $submitterId
     */
    public function getSubmitterId()
    {
        return $this->submitterId;
    }

    public function __toString()
    {
        return $this->getStatusText() . "[#{$this->getId()}]";
    }

    public function __clone()
    {
        $this->id = null;
    }

    /**
     * Set from
     *
     * @param Ojs\WorkflowBundle\Document\ArticleReviewStep $from
     * @return self
     */
    public function setFrom(\Ojs\WorkflowBundle\Document\ArticleReviewStep $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get from
     *
     * @return Ojs\WorkflowBundle\Document\ArticleReviewStep $from
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param Ojs\WorkflowBundle\Document\ArticleReviewStep $to
     * @return self
     */
    public function setTo(\Ojs\WorkflowBundle\Document\ArticleReviewStep $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get to
     *
     * @return Ojs\WorkflowBundle\Document\ArticleReviewStep $to
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set step
     *
     * @param Ojs\WorkflowBundle\Document\JournalWorkflowStep $step
     * @return self
     */
    public function setStep(\Ojs\WorkflowBundle\Document\JournalWorkflowStep $step)
    {
        $this->step = $step;
        return $this;
    }

    /**
     * Get step
     *
     * @return Ojs\WorkflowBundle\Document\JournalWorkflowStep $step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set reviewFormResults
     *
     * @param string $reviewFormResults
     * @return self
     */
    public function setReviewFormResults($reviewFormResults)
    {
        $this->reviewFormResults = $reviewFormResults;
        return $this;
    }

    /**
     * Get reviewFormResults
     *
     * @return string $reviewFormResults
     */
    public function getReviewFormResults()
    {
        return $this->reviewFormResults;
    }

    public function __construct()
    {
        $this->invitations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add invitation
     *
     * @param Ojs\WorkflowBundle\Document\Invitation $invitation
     */
    public function addInvitation(\Ojs\WorkflowBundle\Document\Invitation $invitation)
    {
        $this->invitations[] = $invitation;
    }

    /**
     * Remove invitation
     *
     * @param Ojs\WorkflowBundle\Document\Invitation $invitation
     */
    public function removeInvitation(\Ojs\WorkflowBundle\Document\Invitation $invitation)
    {
        $this->invitations->removeElement($invitation);
    }

    /**
     * Get invitations
     *
     * @return Doctrine\Common\Collections\Collection $invitations
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * Set primaryLanguage
     *
     * @param string $primaryLanguage
     * @return self
     */
    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;
        return $this;
    }

    /**
     * Get primaryLanguage
     *
     * @return string $primaryLanguage
     */
    public function getPrimaryLanguage()
    {
        return $this->primaryLanguage;
    }

    /**
     * Set parentStep
     *
     * @param Ojs\WorkflowBundle\Document\ArticleReviewStep $parentStep
     * @return self
     */
    public function setParentStep(\Ojs\WorkflowBundle\Document\ArticleReviewStep $parentStep)
    {
        $this->parentStep = $parentStep;
        return $this;
    }

    /**
     * Get parentStep
     *
     * @return Ojs\WorkflowBundle\Document\ArticleReviewStep $parentStep
     */
    public function getParentStep()
    {
        return $this->parentStep;
    }


    /**
     * Set competingOfInterest
     *
     * @param string $competingOfInterest
     * @return self
     */
    public function setCompetingOfInterest($competingOfInterest)
    {
        $this->competingOfInterest = $competingOfInterest;
        return $this;
    }

    /**
     * Get competingOfInterest
     *
     * @return string $competingOfInterest
     */
    public function getCompetingOfInterest()
    {
        return $this->competingOfInterest;
    }
}
