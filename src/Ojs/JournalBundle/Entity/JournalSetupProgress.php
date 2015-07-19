<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;

/**
 * Journal Setup Progress
 */
class JournalSetupProgress implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $currentStep;

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
     * Get currentStep
     *
     * @return integer
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    /**
     * Set currentStep
     *
     * @param  string         $currentStep
     * @return $this
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal
     *
     * @param  Journal        $journal
     * @return JournalSetupProgress
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User     $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
