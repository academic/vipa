<?php

namespace Ojs\JournalBundle\Entity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;

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
     * @var City
     * @Expose
     */
    private $city;
    /**
     * @var integer
     * @Expose
     */
    private $city_id;
    
    /**
     * @var Country
     * @Expose
     */
    private $country;

    /**
     * @var string
     * @Expose
     */
    private $country_id;
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
     * @var string
     * @Expose
     */
    private $wiki;
    
    /**
     * @var string
     * @Expose
     */
    private $logo;

    /**
     * @var string
     * @Expose
     */
    private $header;

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }
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
     * @return Institution
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
     * Set city
     *
     * @param  City      $city
     * @return Institution
     */
    public function setCity(City $city) {
        $this->city = $city;
        $this->city_id = $city->getId();
        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity() {
        return $this->city;
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
     * @param  Country     $country
     * @return Institution
     */
    public function setCountry(Country $country) {
        $this->country = $country;
        $this->country_id = $country->getId();
        return $this;
    }

    /**
     * Get country
     *
     * @return Country
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
    
    /**
     * Set wiki
     *
     * @param  string      $wiki
     * @return Institution
     */
    public function setWiki($wiki) {
        $this->wiki = $wiki;

        return $this;
    }

    /**
     * Get wiki
     *
     * @return string
     */
    public function getWiki() {
        return $this->wiki;
    }
    
    /**
     * Set logo
     *
     * @param  string      $logo
     * @return Institution
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    private $slug;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    private $institution_type;

    /**
     * @return mixed
     */
    public function getInstitutionType()
    {
        return $this->institution_type;
    }

    /**
     * @param mixed $institution_type
     * @return $this
     */
    public function setInstitutionType(InstitutionTypes $institution_type)
    {
        $this->institution_type = $institution_type;
        $this->setInstitutionTypeId($institution_type->getId());
        return $this;
    }

    private  $institution_type_id;

    /**
     * @return mixed
     */
    public function getInstitutionTypeId()
    {
        return $this->institution_type_id;
    }

    /**
     * @param mixed $institution_type_id
     * @return $this
     */
    public function setInstitutionTypeId($institution_type_id)
    {
        $this->institution_type_id = $institution_type_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * @param int $city_id
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param string $country_id
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
        return $this;
    }

}
