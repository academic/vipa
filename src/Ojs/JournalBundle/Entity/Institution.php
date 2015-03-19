<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericExtendedEntity;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Institution
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,name,address,email,verified")
 */
class Institution extends GenericExtendedEntity {

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    private $id;
    private $lft;
    private $lvl;
    private $rgt;
    private $root;

    /*
     * @var Institution
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $parent;
    private $children;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="name")
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="address")
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

    public function setParent(Institution $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * 
     * @return Institution
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getRoot()
    {
        return $this->root;
    }

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
    
       /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $authors;

    /**
     *
     */
    public function __construct()
    {
        $this->journals = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    /**
     * Add journal
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return Institution
     */
    public function addJournal(Journal $journal)
    {
        $this->journals[] = $journal;
        return $this;
    }

    /**
     * Remove journal
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     */
    public function removeJournal(Journal $journal)
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param  City $city
     * @return Institution
     */
    public function setCity(City $city)
    {
        $this->city = $city;
        $this->city_id = $city->getId();
        return $this;
    }

    /**
     * Get city
     *
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Institution
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
     * Set address
     *
     * @param  string $address
     * @return Institution
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * Set about
     *
     * @param  string $about
     * @return Institution
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set country
     *
     * @param  Country $country
     * @return Institution
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        $this->country_id = $country->getId();
        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set addressLat
     *
     * @param  string $addressLat
     * @return Institution
     */
    public function setAddressLat($addressLat)
    {
        $this->addressLat = $addressLat;

        return $this;
    }

    /**
     * Get addressLat
     *
     * @return string
     */
    public function getAddressLat()
    {
        return $this->addressLat;
    }

    /**
     * Set addressLong
     *
     * @param  string $addressLong
     * @return Institution
     */
    public function setAddressLong($addressLong)
    {
        $this->addressLong = $addressLong;

        return $this;
    }

    /**
     * Get addressLong
     *
     * @return string
     */
    public function getAddressLong()
    {
        return $this->addressLong;
    }

    /**
     * Set phone
     *
     * @param  string $phone
     * @return Institution
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

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
     * Set fax
     *
     * @param  string $fax
     * @return Institution
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

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
     * Set email
     *
     * @param  string $email
     * @return Institution
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Set url
     *
     * @param  string $url
     * @return Institution
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set wiki
     *
     * @param  string $wiki
     * @return Institution
     */
    public function setWiki($wiki)
    {
        $this->wiki = $wiki;

        return $this;
    }

    /**
     * Get wiki
     *
     * @return string
     */
    public function getWiki()
    {
        return $this->wiki;
    }

    /**
     * Set logo
     *
     * @param  string $logo
     * @return Institution
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

    private $institution_type_id;

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
     * @return $this
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
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
        return $this;
    }

    /**
     * @var boolean
     * @GRID\Column(title="verified")
     */
    private $verified;

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    } 

    


    /**
     * Set lft
     *
     * @param integer $lft
     * @return Institution
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Institution
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Institution
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Institution
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }
  
    /**
     * Get verified
     *
     * @return boolean 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Add authors
     *
     * @param \Ojs\JournalBundle\Entity\Author $authors
     * @return Institution
     */
    public function addAuthor(\Ojs\JournalBundle\Entity\Author $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param \Ojs\JournalBundle\Entity\Author $authors
     */
    public function removeAuthor(\Ojs\JournalBundle\Entity\Author $authors)
    {
        $this->authors->removeElement($authors);
    }

    /**
     * Get authors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Add children
     *
     * @param \Ojs\JournalBundle\Entity\Institution $children
     * @return Institution
     */
    public function addChild(\Ojs\JournalBundle\Entity\Institution $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Ojs\JournalBundle\Entity\Institution $children
     */
    public function removeChild(\Ojs\JournalBundle\Entity\Institution $children)
    {
        $this->children->removeElement($children);
    }
    
    public function __toString()
    {
        return  $this->name;
    }

    /**
     * @var string
     */
    protected $logo_options;
    /**
     * @var string
     */
    protected $header_options;

    /**
     * @return string
     */
    public function getHeaderOptions()
    {
        return $this->header_options;
    }

    /**
     * @param string $header_options
     * @return $this
     */
    public function setHeaderOptions($header_options)
    {
        $this->header_options = $header_options;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogoOptions()
    {
        return $this->logo_options;
    }

    /**
     * @param string $logo_options
     * @return $this
     */
    public function setLogoOptions($logo_options)
    {
        $this->logo_options = $logo_options;
        return $this;
    }

}
