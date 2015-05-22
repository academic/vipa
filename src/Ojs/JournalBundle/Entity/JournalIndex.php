<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * JournalIndex
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

    /** @var  string */
    protected $logo_options;

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
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
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
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
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

    /**
     * @return string
     */
    public function getLogoOptions()
    {
        return $this->logo_options;
    }

    /**
     * @param string $logoOptions
     */
    public function setLogoOptions($logoOptions)
    {
        $this->logo_options = $logoOptions;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
