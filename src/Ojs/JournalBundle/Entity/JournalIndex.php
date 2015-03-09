<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @param string $name
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
     * @param string $logo
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
     * @param boolean $status
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals_indexs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->journals_indexs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add journals_indexs
     *
     * @param \Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs
     * @return JournalIndex
     */
    public function addJournalsIndex(\Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs[] = $journalsIndexs;

        return $this;
    }

    /**
     * Remove journals_indexs
     *
     * @param \Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs
     */
    public function removeJournalsIndex(\Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs->removeElement($journalsIndexs);
    }

    /**
     * Get journals_indexs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJournalsIndexs()
    {
        return $this->journals_indexs;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
