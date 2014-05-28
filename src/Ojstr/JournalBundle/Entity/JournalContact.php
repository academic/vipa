<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JournalContact
 */
class JournalContact {

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
     * @var \Ojstr\JournalBundle\Entity\Contact
     */
    private $contact;

    /**
     *
     * @var \Ojstr\JournalBundle\Entity\ContactTypes
     */
    private $contactType;

    /**
     *
     * @var \Ojstr\JournalBundle\Entity\Journal 
     */
    private $journal;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set journalId
     *
     * @param integer $journalId
     * @return JournalContact
     */
    public function setJournalId($journalId) {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer 
     */
    public function getJournalId() {
        return $this->journalId;
    }

    /**
     * Set contactId
     *
     * @param integer $contactId
     * @return JournalContact
     */
    public function setContactId($contactId) {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * Get contactId
     *
     * @return integer 
     */
    public function getContactId() {
        return $this->contactId;
    }

    /**
     * Set contactTypeId
     *
     * @param integer $contactTypeId
     * @return JournalContact
     */
    public function setContactTypeId($contactTypeId) {
        $this->contactTypeId = $contactTypeId;

        return $this;
    }

    /**
     * Get contactTypeId
     *
     * @return integer 
     */
    public function getContactTypeId() {
        return $this->contactTypeId;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\ContactTypes $contactType
     * @return \Ojstr\JournalBundle\Entity\JournalContact
     */
    public function setContactType(\Ojstr\JournalBundle\Entity\ContactTypes $contactType) {
        $this->contactType = $contactType;
        return $this;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\Contact $contact
     * @return \Ojstr\JournalBundle\Entity\JournalContact
     */
    public function setContact(\Ojstr\JournalBundle\Entity\Contact $contact) {
        $this->contact = $contact;
        return $this;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\Journal $journal
     * @return \Ojstr\JournalBundle\Entity\JournalContact
     */
    public function setJournal(\Ojstr\JournalBundle\Entity\Journal $journal) {
        $this->journal = $journal;
        return $this;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\Journal
     */
    public function getJournal() {
        return $this->journal;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\Contact
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\ContactTypes
     */
    public function getcontactType() {
        return $this->contactType;
    }

}
