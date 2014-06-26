<?php

namespace Ojstr\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * 
 * @MongoDb\Document(collection="journal_workflow_steps") 
 */
class JournalWorkflowStep {

    /**
     * @MongoDb\Id
     */
    protected $id;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $journal_id;

    /** @MongoDb\String */
    protected $title;

    /** @MongoDb\Boolean */
    protected $first_step;

    /** @MongoDb\Boolean */
    protected $last_step;

    /**
     * possible next steps
     * @MongoDB\Hash 
     */
    private $next_steps;

    /**
     * 
     * @MongoDB\Hash 
     */
    private $roles;

    /**
     * Default deadline for this step for review
     * @MongoDB\Date
     */
    protected $deadline;


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
        $this->journal_id = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return int $journalId
     */
    public function getJournalId()
    {
        return $this->journal_id;
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

    /**
     * Set firstStep
     *
     * @param boolean $firstStep
     * @return self
     */
    public function setFirstStep($firstStep)
    {
        $this->first_step = $firstStep;
        return $this;
    }

    /**
     * Get firstStep
     *
     * @return boolean $firstStep
     */
    public function getFirstStep()
    {
        return $this->first_step;
    }

    /**
     * Set lastStep
     *
     * @param boolean $lastStep
     * @return self
     */
    public function setLastStep($lastStep)
    {
        $this->last_step = $lastStep;
        return $this;
    }

    /**
     * Get lastStep
     *
     * @return boolean $lastStep
     */
    public function getLastStep()
    {
        return $this->last_step;
    }

    /**
     * Set nextSteps
     *
     * @param hash $nextSteps
     * @return self
     */
    public function setNextSteps($nextSteps)
    {
        $this->next_steps = $nextSteps;
        return $this;
    }

    /**
     * Get nextSteps
     *
     * @return hash $nextSteps
     */
    public function getNextSteps()
    {
        return $this->next_steps;
    }

    /**
     * Set roles
     *
     * @param hash $roles
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
     * @return hash $roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set deadline
     *
     * @param date $deadline
     * @return self
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * Get deadline
     *
     * @return date $deadline
     */
    public function getDeadline()
    {
        return $this->deadline;
    }
}
