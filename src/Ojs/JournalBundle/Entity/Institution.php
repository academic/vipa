<?php

namespace Ojs\JournalBundle\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
/**
 * Institution
 * @ExclusionPolicy("all")
 */
class Institution extends \Ojs\Common\Entity\GenericExtendedEntity {

    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $name;

    /**
     * @var string
     * @Expose
     */
    private $address;
    
    /**
     * @var string
     * @Expose
     */
    private $about;

    /**
     * @var integer
     * @Expose
     */
    private $country;

    /**
     * @var string
     * @Expose
     */
    private $addressLat;

    /**
     * @var string
     * @Expose
     */
    private $addressLong;

    /**
     * @var string
     * @Expose
     */
    private $phone;

    /**
     * @var string
     * @Expose
     */
    private $fax;

    /**
     * @var string
     * @Expose
     */
    private $email;

    /**
     * @var string
     * @Expose
     */
    private $url;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals;
    
    public function __construct()
    {
        $this->journals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add journal
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return Language
     */
    public function addJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journals[] = $journal;
        return $this;
    }

    /**
     * Remove journal
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     */
    public function removeJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string      $name
     * @return Institution
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param  string      $address
     * @return Institution
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set about
     *
     * @param  string      $about
     * @return Institution
     */
    public function setAbout($about) {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout() {
        return $this->about;
    }

    /**
     * Set country
     *
     * @param  integer     $country
     * @return Institution
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return integer
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set addressLat
     *
     * @param  string      $addressLat
     * @return Institution
     */
    public function setAddressLat($addressLat) {
        $this->addressLat = $addressLat;

        return $this;
    }

    /**
     * Get addressLat
     *
     * @return string
     */
    public function getAddressLat() {
        return $this->addressLat;
    }

    /**
     * Set addressLong
     *
     * @param  string      $addressLong
     * @return Institution
     */
    public function setAddressLong($addressLong) {
        $this->addressLong = $addressLong;

        return $this;
    }

    /**
     * Get addressLong
     *
     * @return string
     */
    public function getAddressLong() {
        return $this->addressLong;
    }

    /**
     * Set phone
     *
     * @param  string      $phone
     * @return Institution
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param  string      $fax
     * @return Institution
     */
    public function setFax($fax) {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param  string      $email
     * @return Institution
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param  string      $url
     * @return Institution
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

}
