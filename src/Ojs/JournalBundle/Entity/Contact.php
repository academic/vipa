<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Okulbilisim\LocationBundle\Entity\Country;
use Okulbilisim\LocationBundle\Entity\Province;

/**
 * Contact
 * @GRID\Source(columns="id,title,firstName,lastName,country,phone,email")
 */
class Contact implements Translatable
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
    private $id;
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
     * @GRID\Column(title="phone")
     */
    private $phone;
    /**
     * @var string
     */
    private $fax;
    /**
     * @var string
     * @GRID\Column(title="email")
     */
    private $email;

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
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string  $title
     * @return Contact
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param  string  $firstName
     * @return Contact
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
     * @param  string  $lastName
     * @return Contact
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
     * @param  string  $address
     * @return Contact
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
     * @param  string  $phone
     * @return Contact
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
     * @param  string  $fax
     * @return Contact
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
     * @param  string  $email
     * @return Contact
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
}
