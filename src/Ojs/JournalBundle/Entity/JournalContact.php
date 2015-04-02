<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * JournalContact
 * @GRID\Source(columns="id,journal.title,contact.email,contactType.name")
 */
class JournalContact extends GenericExtendedEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var integer
     */
    private $contactId;

    /**
     * @var integer
     */
    private $contactTypeId;

    /**
     * @var \Ojs\JournalBundle\Entity\Contact
     * @GRID\Column(field="contact.email",title="Contact")
     */
    private $contact;

    /**
     *
     * @var \Ojs\JournalBundle\Entity\ContactTypes
     * @GRID\Column(field="contactType.name",title="Contact Type")
     */
    private $contactType;

    /**
     *
     * @var \Ojs\JournalBundle\Entity\Journal
     * @GRID\Column(field="journal.title",title="Journal")
     */
    private $journal;

    public function __construct()
    {
        parent::__construct();
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
     * Set journalId
     *
     * @param  integer        $journalId
     * @return JournalContact
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
     * Set contactId
     *
     * @param  integer        $contactId
     * @return JournalContact
     */
    public function setContactId($contactId)
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * Get contactId
     *
     * @return integer
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * Set contactTypeId
     *
     * @param  integer        $contactTypeId
     * @return JournalContact
     */
    public function setContactTypeId($contactTypeId)
    {
        $this->contactTypeId = $contactTypeId;

        return $this;
    }

    /**
     * Get contactTypeId
     *
     * @return integer
     */
    public function getContactTypeId()
    {
        return $this->contactTypeId;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\ContactTypes   $contactType
     * @return \Ojs\JournalBundle\Entity\JournalContact
     */
    public function setContactType(\Ojs\JournalBundle\Entity\ContactTypes $contactType)
    {
        $this->contactType = $contactType;

        return $this;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Contact        $contact
     * @return \Ojs\JournalBundle\Entity\JournalContact
     */
    public function setContact(\Ojs\JournalBundle\Entity\Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Journal        $journal
     * @return \Ojs\JournalBundle\Entity\JournalContact
     */
    public function setJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Get contactType
     *
     * @return \Ojs\JournalBundle\Entity\ContactTypes 
     */
    public function getContactType()
    {
        return $this->contactType;
    }

}
