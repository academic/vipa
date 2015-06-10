<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalContact
 * @GRID\Source(columns="id,journal.title,contact.email,contactType.name")
 */
class JournalContact implements Translatable
{
    use GenericEntityTrait;

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
     * @var Contact
     * @GRID\Column(field="contact.email",title="Contact")
     */
    private $contact;

    /**
     *
     * @var ContactTypes
     * @GRID\Column(field="contactType.name",title="Contact Type")
     */
    private $contactType;

    /**
     *
     * @var Journal
     * @GRID\Column(field="journal.title",title="Journal")
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
     * @param  integer $journalId
     * @return $this
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
     * @param  integer $contactId
     * @return $this
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
     * @param  integer $contactTypeId
     * @return $this
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
     * @param  ContactTypes $contactType
     * @return $this
     */
    public function setContactType(ContactTypes $contactType)
    {
        $this->contactType = $contactType;

        return $this;
    }

    /**
     *
     * @param  Contact $contact
     * @return $this
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     *
     * @param  Journal $journal
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     *
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Get contactType
     *
     * @return ContactTypes
     */
    public function getContactType()
    {
        return $this->contactType;
    }
}
