<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use BulutYazilim\LocationBundle\Entity\Country;
use BulutYazilim\LocationBundle\Entity\Province;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * JournalContact
 * @GRID\Source(columns="id, title, fullName")
 * @GRID\Source(columns="id, journal.translations.title, fullName, contactType.translations.name", groups={"admin"})
 */
class JournalContact implements JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     * @var PersonTitle
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @var string
     * @GRID\Column(title="firstname")
     */
    private $fullName;

    /**
     * @var string
     */
    protected $affiliation;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * @var ContactTypes
     * @GRID\Column(title="contactType", field="contactType.translations.name")
     */
    private $contactType;

    /**
     * @var Province
     */
    protected $city;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Journal
     * @GRID\Column(title="journal", field="journal.translations.title", safe=false)
     */
    private $journal;

    /**
     * @var Institution
     */
    private $institution;

    /**
     * Get ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return PersonTitle
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  PersonTitle $title
     * @return JournalContact
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set firstName
     *
     * @param  string $fullName
     * @return JournalContact
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Set phone
     *
     * @param  string $phone
     * @return JournalContact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param  string         $email
     * @return JournalContact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param  string $address
     * @return JournalContact
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return Province
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param  string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param  Country $country
     * @return $this
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }

    /**
     * @param  string $affiliation
     * @return $this
     */
    public function setAffiliation($affiliation)
    {
        $this->affiliation = $affiliation;

        return $this;
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

    /**
     * @param ContactTypes|null $contactType
     * @return $this
     */
    public function setContactType(ContactTypes $contactType = null)
    {
        $this->contactType = $contactType;
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
     * @param  Journal $journal
     * @return $this
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     *
     * @param  Institution $institution
     * @return $this
     */
    public function setInstitution(Institution $institution = null)
    {
        $this->institution = $institution;
        return $this;
    }

    public function __toString()
    {
        return $this->getFullName();
    }
}
