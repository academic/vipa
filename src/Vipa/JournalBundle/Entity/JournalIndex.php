<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * JournalIndex
 * @GRID\Source(columns="id,journal,journal_index.name,link")
 */
class JournalIndex implements JournalItemInterface
{
    use DisplayTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="link")
     */
    private $link;

    /**
     * @var Journal
     * @Grid\Column(title="journal")
     */
    private $journal;

    /**
     * @var Index
     * @GRID\Column(title="Journal Index", field="index.name")
     */
    private $index;

    /**
     * @var bool
     */
    private $verified = 0;

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
     * @param  string $link
     * @return JournalIndex
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
     * @param  Journal $journal
     * @return JournalIndex
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get index
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set index
     *
     * @param  Index $index
     * @return JournalIndex
     */
    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     *
     * @return $this
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getIndex()->getName();
    }
}
