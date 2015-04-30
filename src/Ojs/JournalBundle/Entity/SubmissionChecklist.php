<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * SubmissionChecklist
 * @GRID\Source(columns="id,label,locale,visible")
 */
class SubmissionChecklist
{
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="submission_checklist.label",safe = false)
     */
    private $label;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var integer
     */
    private $journal_id;

    /**
     * @var boolean
     * @GRID\Column(title="submission_checklist.visible")
     */
    private $visible;

    /**
     * @var \DateTime
     */
    private $deletedAt;


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
     * Set label
     *
     * @param string $label
     * @return SubmissionChecklist
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return SubmissionChecklist
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set journal_id
     *
     * @param integer $journalId
     * @return SubmissionChecklist
     */
    public function setJournalId($journalId)
    {
        $this->journal_id = $journalId;

        return $this;
    }

    /**
     * Get journal_id
     *
     * @return integer 
     */
    public function getJournalId()
    {
        return $this->journal_id;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return SubmissionChecklist
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return SubmissionChecklist
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    /**
     * @var \Ojs\JournalBundle\Entity\Journal
     */
    private $journal;


    /**
     * Set journal
     *
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return SubmissionChecklist
     */
    public function setJournal(\Ojs\JournalBundle\Entity\Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojs\JournalBundle\Entity\Journal 
     */
    public function getJournal()
    {
        return $this->journal;
    }
    /**
     * @var string
     * @GRID\Column(title="Locale")
     */
    private $locale;


    /**
     * Set locale
     *
     * @param string $locale
     * @return SubmissionChecklist
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
