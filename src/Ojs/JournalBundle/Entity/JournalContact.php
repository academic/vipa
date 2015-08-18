<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\JournalBundle\Entity\ArticleTypesTranslation;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\LocationBundle\Entity\Country;
use Ojs\LocationBundle\Entity\Province;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * JournalContact
 * @GRID\Source(columns="id,title,firstName,lastName,contactTypeName")
 */
class JournalContact extends AbstractTranslatable
{
    use GenericEntityTrait;

    /** @var  Country */
    protected $country;

    /** @var  Province */
    protected $city;

    /** @var  string */
    protected $affiliation;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     * @var string
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @var string
     * @GRID\Column(title="firstname")
     */
    private $firstName;

    /**
     * @var string
     * @GRID\Column(title="lastname")
     */
    private $lastName;

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
    private $fax;

    /**
     * @var string
     */
    private $email;

    /**
     *
     * @var ContactTypes
     */
    private $contactType;

    /**
     * @var ContactTypes
     * @GRID\Column(title="Contact Type")
     */
    private $contactTypeName;

    /**
     *
     * @var Journal
     */
    private $journal;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalContactTranslation")
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalContactTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new JournalContactTranslation();
            if(!is_null($defaultTranslation)){
                $translation->setTitle($defaultTranslation->getTitle());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;
        return $translation;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * Set title
     *
     * @param  string         $title
     * @return JournalContact
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param  string         $firstName
     * @return JournalContact
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param  string         $lastName
     * @return JournalContact
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

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
     * @param  string         $address
     * @return JournalContact
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * @return Province
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param  Province $city
     * @return $this
     */
    public function setCity(Province $city)
    {
        $this->city = $city;

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
     * Set phone
     *
     * @param  string         $phone
     * @return JournalContact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set fax
     *
     * @param  string         $fax
     * @return JournalContact
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

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

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * Get firstName+lastName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName." ".$this->lastName;
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
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get contactType
     *
     * @return ContactTypes
     */
    public function getContactType()
    {
        $this->contactType->setDefaultLocale($this->getDefaultLocale());
        return $this->contactType;
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
}
