<?php

namespace Vipa\JournalBundle\Entity;

/**
 * Numerator
 */
class Numerator implements JournalItemInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $last;

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
     * @param Journal $journal
     * @return Numerator
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Numerator
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set last
     *
     * @param integer $last
     *
     * @return Numerator
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    /**
     * Get last
     *
     * @return integer
     */
    public function getLast()
    {
        return $this->last;
    }
}

