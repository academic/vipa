<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * JournalLicence
 * @GRID\Source(columns="id,label,visible")
 */
class JournalLicence
{
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="journal_licence.label")
     */
    private $label;

    /**
     * @var string
     */
    private $licence;

    /**
     * @var integer
     */
    private $journal_id;

    /**
     * @var boolean
     * @GRID\Column(title="journal_licence.visible")
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
     * @return JournalLicence
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
     * Set licence
     *
     * @param string $licence
     * @return JournalLicence
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence
     *
     * @return string 
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * Set journal_id
     *
     * @param integer $journalId
     * @return JournalLicence
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
     * @return JournalLicence
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
     * @return JournalLicence
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
     * @return JournalLicence
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
}
