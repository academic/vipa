<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;

/**
 * This collection holds resumable article submission data
 * @GRID\Source(columns="id, currentStep, journalId , journal")
 */
class ArticleSubmissionProgress implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     * @var integer
     * @GRID\Column(title="current.step")
     */
    protected $currentStep;
    /**
     * @var string
     */
    protected $competingOfInterest;
    /**
     * @var string
     */
    protected $primaryLanguage;
    /**
     * @var User
     */
    private $user;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var Journal
     */
    private $journal;
    /**
     * @var int
     */
    private $journalId;
    /**
     * @var Article
     */
    private $article;

    /**
     * @var boolean
     */
    private $submitted;

    /**
     * @var string
     */
    private $checklist;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get currentStep
     *
     * @return int $currentStep
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    /**
     * Set currentStep
     *
     * @param  int  $currentStep
     * @return self
     */
    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;

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
     * Set journalId
     *
     * @param  int  $journalId
     * @return self
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

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
     * Set userId
     *
     * @param  int  $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return boolean $submitted
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set submitted
     *
     * @param  boolean $submitted
     * @return self
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * @param $checklist
     */
    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;
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

    /**
     * Set competingOfInterest
     *
     * @param  string $competingOfInterest
     * @return self
     */
    public function setCompetingOfInterest($competingOfInterest)
    {
        $this->competingOfInterest = $competingOfInterest;

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
     * @param  Journal $journal
     * @return $this
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

    /**
     * Get article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set article
     *
     * @param  Article     $article
     * @return $this
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}
