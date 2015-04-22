<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Board
 *  @GRID\Source(columns="id , journal.title, name, description") 
 */
class Board extends GenericExtendedEntity {

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var string
     * @GRID\Column(title="name")
     */
    private $name;

    /**
     * @var string
     * @GRID\Column(title="description")
     */
    private $description;
    
        /**
     * @var \Ojs\JournalBundle\Entity\Journal
     * @GRID\Column(field="journal.title", title="journal")
     */
    private $journal;

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
     * Set journalId
     *
     * @param integer $journalId
     * @return Board
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

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
     * Set name
     *
     * @param string $name
     * @return Board
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
     * Set description
     *
     * @param string $description
     * @return Board
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    } 

    /**
     * Set journal
     *
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return Board
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
