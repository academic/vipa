<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;
use Ojs\JournalBundle\Entity\Journal;
use Doctrine\Common\Collections\Collection;

/**
 * This collection holds resumable article submission data
 * @GRID\Source(columns="id,journal_id,article_data")
 */
class ArticleSubmissionProgress implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $currentStep;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    protected $competingOfInterest;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     */
    protected $primaryLanguage;

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
     * Get currentStep
     *
     * @return int $currentStep
     */
    public function getCurrentStep()
    {
        return $this->currentStep;
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
     * Get submitted
     *
     * @return boolean $submitted
     */
    public function getSubmitted()
    {
        return $this->submitted;
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
     * Get competingOfInterest
     *
     * @return string $competingOfInterest
     */
    public function getCompetingOfInterest()
    {
        return $this->competingOfInterest;
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
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
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
     * @return User
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
