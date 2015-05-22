<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 *
 * @MongoDb\Document(collection="journal_workflow_steps",repositoryClass="Ojs\WorkflowBundle\Repository\JournalWorkflowStepRepository")
 */
class JournalWorkflowStep
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /** @MongoDb\Boolean */
    protected $shouldFileCi;

    /** @MongoDb\String */
    protected $ciText;

    /** @MongoDb\Int */
    protected $journalid;

    /** @MongoDb\String */
    protected $title;

    /** @MongoDb\String */
    protected $color;

    /** @MongoDb\String */
    protected $status;

    /** @MongoDb\Boolean */
    protected $firstStep;

    /** @MongoDb\Boolean */
    protected $lastStep;

    /** @MongoDb\Boolean */
    protected $onlyreply;

    /** @MongoDb\Boolean */
    protected $canEdit;

    /** @MongoDb\Boolean */
    protected $canReview;

    /** @MongoDb\Boolean */
    protected $canRejectSubmission;

    /** @MongoDb\Boolean */
    protected $mustBeAssigned;

    /** @MongoDb\String */
    protected $introduction;

    /**
     *  @MongoDb\ReferenceMany(targetDocument="ReviewForm",nullable=true)
     */
    private $reviewForms;

    /**
     * @MongoDb\ReferenceMany(targetDocument="JournalWorkflowStep",nullable=true)
     * @var Collection
     */
    private $nextSteps;

    /**
     *
     *  {
     * 	"0" : {
     * 		"id" : 7,
     * 		"name" : "Editor",
     * 		"role" : "ROLE_EDITOR"
     * 	},
     * 	"1" : {
     * 		"id" : 11,
     * 		"name" : "Copyeditor",
     * 		"role" : "ROLE_COPYEDITOR"
     * 	}
     * },
     * @MongoDB\Hash
     */
    private $roles;

    /**
     * Default max days for this step for review
     * @MongoDB\Int
     */
    protected $maxdays;

    /** @MongoDb\Boolean */
    protected $canSeeAuthor = true;

    /** @MongoDb\Boolean */
    protected $isVisible = true;

    /**
     * Document Id
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set journalid
     *
     * @param  int  $journalid
     * @return self
     */
    public function setJournalid($journalid)
    {
        $this->journalid = $journalid;

        return $this;
    }

    /**
     * Get journalid
     *
     * @return int $journalid
     */
    public function getJournalid()
    {
        return $this->journalid;
    }

    /**
     * Set title
     *
     * @param  string $title
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

    /**
     * Set status
     *
     * @param  string $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set firstStep
     *
     * @param  boolean $firstStep
     * @return self
     */
    public function setFirstStep($firstStep)
    {
        $this->firstStep = $firstStep;

        return $this;
    }

    /**
     * Get firstStep
     *
     * @return boolean $firstStep
     */
    public function getFirstStep()
    {
        return $this->firstStep;
    }

    /**
     * Set lastStep
     *
     * @param  boolean $lastStep
     * @return self
     */
    public function setLastStep($lastStep)
    {
        $this->lastStep = $lastStep;

        return $this;
    }

    /**
     * Get lastStep
     *
     * @return boolean $lastStep
     */
    public function getLastStep()
    {
        return $this->lastStep;
    }

    /**
     * Set roles in array format
     * $serializer->serialize($role, 'json')); can be used to generate array from document
     * @param  $roles
     * @return self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return $roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set maxdays
     *
     * @param  int  $maxdays
     * @return self
     */
    public function setMaxdays($maxdays)
    {
        $this->maxdays = $maxdays;

        return $this;
    }

    /**
     * Get maxdays
     *
     * @return int $maxdays
     */
    public function getMaxdays()
    {
        return $this->maxdays;
    }

    /**
     * Set onlyreply
     *
     * @param  boolean $onlyreply
     * @return self
     */
    public function setOnlyreply($onlyreply)
    {
        $this->onlyreply = $onlyreply;

        return $this;
    }

    /**
     * Get onlyreply
     *
     * @return boolean $onlyreply
     */
    public function getOnlyreply()
    {
        return $this->onlyreply;
    }

    /**
     * Set canSeeAuthor
     *
     * @param  boolean $canSeeAuthor
     * @return self
     */
    public function setCanSeeAuthor($canSeeAuthor)
    {
        $this->canSeeAuthor = $canSeeAuthor;

        return $this;
    }

    /**
     * Get canSeeAuthor
     *
     * @return boolean $canSeeAuthor
     */
    public function getCanSeeAuthor()
    {
        return $this->canSeeAuthor;
    }

    /**
     * Set isVisible
     *
     * @param  boolean $isVisible
     * @return self
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean $isVisible
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set canEdit
     *
     * @param  boolean $canEdit
     * @return self
     */
    public function setCanEdit($canEdit)
    {
        $this->canEdit = $canEdit;

        return $this;
    }

    /**
     * Get canEdit
     *
     * @return boolean $canEdit
     */
    public function getCanEdit()
    {
        return $this->canEdit;
    }

    /**
     * Set mustBeAssigned
     *
     * @param  boolean $mustBeAssigned
     * @return self
     */
    public function setMustBeAssigned($mustBeAssigned)
    {
        $this->mustBeAssigned = $mustBeAssigned;

        return $this;
    }

    /**
     * Get mustBeAssigned
     *
     * @return boolean $mustBeAssigned
     */
    public function getMustBeAssigned()
    {
        return $this->mustBeAssigned;
    }

    public function __construct()
    {
        $this->reviewForms = new ArrayCollection();
    }

    /**
     * Add reviewForm
     *
     * @param ReviewForm $reviewForm
     */
    public function addReviewForm(ReviewForm $reviewForm)
    {
        $this->reviewForms[] = $reviewForm;
    }

    /**
     * Remove reviewForm
     *
     * @param ReviewForm $reviewForm
     */
    public function removeReviewForm(ReviewForm $reviewForm)
    {
        $this->reviewForms->removeElement($reviewForm);
    }

    /**
     * Get reviewForms
     *
     * @return Collection $reviewForms
     */
    public function getReviewForms()
    {
        return $this->reviewForms;
    }

    /**
     *
     * @param  ReviewForm $reviewForm
     * @return boolean
     */
    public function hasForm($reviewForm)
    {
        foreach ($this->getReviewForms() as $form) {
            if ($form->getId() === $reviewForm->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    public function removeAllReviewForms()
    {
        $this->reviewForms = [];
    }

    /**
     * Set color
     *
     * @param  string $color
     * @return self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string $color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set canReview
     *
     * @param  boolean $canReview
     * @return self
     */
    public function setCanReview($canReview)
    {
        $this->canReview = $canReview;

        return $this;
    }

    /**
     * Get canReview
     *
     * @return boolean $canReview
     */
    public function getCanReview()
    {
        return $this->canReview;
    }

    /**
     * Set canRejectSubmission
     *
     * @param  boolean $canRejectSubmission
     * @return self
     */
    public function setCanRejectSubmission($canRejectSubmission)
    {
        $this->canRejectSubmission = $canRejectSubmission;

        return $this;
    }

    /**
     * Get canRejectSubmission
     *
     * @return boolean $canRejectSubmission
     */
    public function getCanRejectSubmission()
    {
        return $this->canRejectSubmission;
    }

    /**
     * Add nextStep
     *
     * @param JournalWorkflowStep $nextStep
     */
    public function addNextStep(JournalWorkflowStep $nextStep)
    {
        $this->nextSteps[] = $nextStep;
    }

    /**
     *  remove all next steps
     * @return self
     */
    public function clearNextSteps()
    {
        $this->nextSteps = null;

        return $this;
    }

    /**
     * Remove nextStep
     *
     * @param JournalWorkflowStep $nextStep
     */
    public function removeNextStep(JournalWorkflowStep $nextStep)
    {
        $this->nextSteps->removeElement($nextStep);
    }

    /**
     * Get nextSteps
     *
     * @return Collection $nextSteps
     */
    public function getNextSteps()
    {
        return $this->nextSteps;
    }

    /**
     * Set introduction
     *
     * @param  string $introduction
     * @return self
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string $introduction
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set shouldFileCi
     *
     * @param  boolean $shouldFileCi
     * @return self
     */
    public function setShouldFileCi($shouldFileCi)
    {
        $this->shouldFileCi = $shouldFileCi;

        return $this;
    }

    /**
     * Get shouldFileCi
     *
     * @return boolean $shouldFileCi
     */
    public function getShouldFileCi()
    {
        return $this->shouldFileCi;
    }

    /**
     * Set ciText
     *
     * @param  string $ciText
     * @return self
     */
    public function setCiText($ciText)
    {
        $this->ciText = $ciText;

        return $this;
    }

    /**
     * Get ciText
     *
     * @return string $ciText
     */
    public function getCiText()
    {
        return $this->ciText;
    }
}
