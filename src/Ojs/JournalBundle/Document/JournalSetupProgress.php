<?php

namespace Ojs\JournalBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Okulbilisim\LocationBundle\Entity\Location;
use Ojs\UserBundle\Entity\UserJournalRole;

/**
 * This collection holds resumable new journal setup data
 * @MongoDb\Document(collection="journal_setup_progress")
 */
class JournalSetupProgress
{
    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * @MongoDb\Int
     */
    protected $current_step;

    /**
     * @MongoDB\Date
     */
    protected $started_date;

    /**
     * @MongoDB\Date
     */
    protected $last_resume_date;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $userId;

    /** @MongoDb\Int @MongoDb\Index() */
    protected $journalId;

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
     * Set currentStep
     *
     * @param int $currentStep
     * @return self
     */
    public function setCurrentStep($currentStep)
    {
        $this->current_step = $currentStep;
        return $this;
    }

    /**
     * Get currentStep
     *
     * @return int $currentStep
     */
    public function getCurrentStep()
    {
        return $this->current_step;
    }

    /**
     * Set startedDate
     *
     * @param \DateTime $startedDate
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
     * @return \DateTime $startedDate
     */
    public function getStartedDate()
    {
        return $this->started_date;
    }

    /**
     * Set lastResumeDate
     *
     * @param \DateTime $lastResumeDate
     * @return self
     */
    public function setLastResumeDate($lastResumeDate)
    {
        $this->last_resume_date = $lastResumeDate;
        return $this;
    }

    /**
     * Get lastResumeDate
     *
     * @return date $lastResumeDate
     */
    public function getLastResumeDate()
    {
        return $this->last_resume_date;
    }

    /**
     * Set userId
     *
     * @param int $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->userId;
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
}
