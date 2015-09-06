<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * JournalsIndex
 * @GRID\Source(columns="id,journal,journal_index.name,link")
 */
class JournalsIndex
{
    use DisplayTrait;
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var integer
     */
    private $journalIndexId;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var string
     * @GRID\Column(title="link")
     */
    private $link;
    /**
     * @var Journal
     * @GRID\Column(title="Journal")
     */
    private $journal;
    /**
     * @var JournalIndex
     * @GRID\Column(title="Journal Index", field="journal_index.name")
     */
    private $journalIndex;

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
     * Get journalIndexId
     *
     * @return integer
     */
    public function getJournalIndexId()
    {
        return $this->journalIndexId;
    }

    /**
     * Set journalIndexId
     *
     * @param  integer       $journalIndexId
     * @return JournalsIndex
     */
    public function setJournalIndexId($journalIndexId)
    {
        $this->journalIndexId = $journalIndexId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set journalId
     *
     * @param  integer       $journalId
     * @return JournalsIndex
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link
     *
     * @param  string        $link
     * @return JournalsIndex
     */
    public function setLink($link)
    {
        $this->link = $link;

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
     * @param  Journal       $journal
     * @return JournalsIndex
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journalIndex
     *
     * @return JournalIndex
     */
    public function getJournalIndex()
    {
        return $this->journalIndex;
    }

    /**
     * Set journalIndex
     *
     * @param  JournalIndex  $journalIndex
     * @return JournalsIndex
     */
    public function setJournalIndex(JournalIndex $journalIndex = null)
    {
        $this->journalIndex = $journalIndex;

        return $this;
    }
}
