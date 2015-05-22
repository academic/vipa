<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * JournalsIndex
 * @GRID\Source(columns="id,journal.title,journal_index.name,link")
 */
class JournalsIndex
{
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var integer
     */
    private $journal_index_id;

    /**
     * @var integer
     */
    private $journal_id;

    /**
     * @var string
     * @GRID\Column(title="link")
     */
    private $link;

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
     * Set journal_index_id
     *
     * @param  integer       $journalIndexId
     * @return JournalsIndex
     */
    public function setJournalIndexId($journalIndexId)
    {
        $this->journal_index_id = $journalIndexId;

        return $this;
    }

    /**
     * Get journal_index_id
     *
     * @return integer
     */
    public function getJournalIndexId()
    {
        return $this->journal_index_id;
    }

    /**
     * Set journal_id
     *
     * @param  integer       $journalId
     * @return JournalsIndex
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
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * @var Journal
     * @GRID\Column(title="Journal",field="journal.title")
     */
    private $journal;

    /**
     * @var JournalIndex
     * @GRID\Column(title="Journal Index", field="journal_index.name")
     */
    private $journal_index;

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
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal_index
     *
     * @param  JournalIndex  $journalIndex
     * @return JournalsIndex
     */
    public function setJournalIndex(JournalIndex $journalIndex = null)
    {
        $this->journal_index = $journalIndex;

        return $this;
    }

    /**
     * Get journal_index
     *
     * @return JournalIndex
     */
    public function getJournalIndex()
    {
        return $this->journal_index;
    }
}
