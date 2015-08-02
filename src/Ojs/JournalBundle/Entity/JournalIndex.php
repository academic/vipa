<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * JournalIndex
 * @GRID\Source(columns="id,name,status")
 */
class JournalIndex
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $logo;
    /**
     * @var boolean
     */
    private $status;
    /**
     * @var Collection
     */
    private $journals_indexs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->journals_indexs = new ArrayCollection();
    }

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
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo
     *
     * @param  string       $logo
     * @return JournalIndex
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param  boolean      $status
     * @return JournalIndex
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Add journals_indexs
     *
     * @param  JournalsIndex $journalsIndexs
     * @return JournalIndex
     */
    public function addJournalsIndex(JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs[] = $journalsIndexs;

        return $this;
    }

    /**
     * Remove journals_indexs
     *
     * @param JournalsIndex $journalsIndexs
     */
    public function removeJournalsIndex(JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs->removeElement($journalsIndexs);
    }

    /**
     * Get journals_indexs
     *
     * @return Collection
     */
    public function getJournalsIndexs()
    {
        return $this->journals_indexs;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param  string       $name
     * @return JournalIndex
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
