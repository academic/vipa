<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\LocationBundle\Entity\Country;
use Ojs\LocationBundle\Entity\Province;

/**
 * Institution
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,name,address,email,verified")
 * @GRID\Source(columns="id,name,status", groups={"application"})
 */
class Institution implements Translatable
{
    use GenericEntityTrait;

    public $statusTexts = array(0 => 'application.status.onhold', 1 => 'application.status.rejected');

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    private $id;
    private $lft;

    /*
     * @var Institution
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $lvl;
    private $rgt;
    private $root;
    private $parent;
    /**
     * @var ArrayCollection|Institution[]
     */
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
     * @var Province
     * @Expose
     * @GRID\Column(field="city.name",title="city")
     */
    private $city;

    /**
     * @var Country
     * @Expose
     * @GRID\Column(field="country.name",title="country")
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
     * @var string
     * @Expose
     */
    private $domain;

    /**
     * @var Collection
     */
    private $journals;
    /**
     * @var Collection
     */
    private $authors;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var int
     */
    private $institution_type;

    /**
     * @var
     */
    private $institution_type_id;

    /**
     * @var integer
     * @Expose
     */
    private $themeId;

    /**
     * @var InstitutionTheme
     */
    private $theme;

    /**
     * @var integer
     */
    private $designId;

    /**
     * @var InstitutionTheme
     */
    private $design;

    /**
     * @var Collection
     * @Expose
     */
    private $institutionThemes;

    /**
     * @var JournalDesign Collection
     * @Expose
     */
    private $institutionDesigns;

    /**
     * @var boolean
     * @GRID\Column(title="verified")
     */
    private $verified = false;

    private $status = 0;

    protected $translations;

    public function __construct()
    {
        $this->journals = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->institutionThemes = new ArrayCollection();
        $this->institutionDesigns = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(InstitutionTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function setTranslations($translations)
    {
        foreach($translations as $translation){
            $this->addTranslation($translation);
        }
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     * @GRID\Column(field="statusText",title="status")
     */
    public function getStatusText()
    {
        return array_key_exists($this->status, $this->statusTexts) ? $this->statusTexts[$this->status] : "-";
    }

    /**
     *
     * @return $this
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Institution $parent = null)
    {
        $this->parent = $parent;
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
     * Set root
     *
     * @param  integer $root
     * @return $this
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param  string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param  string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Add journal
     * @param  Journal $journal
     * @return $this
     */
    public function addJournal(Journal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove journal
     * @param Journal $journal
     */
    public function removeJournal(Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     * @return Collection
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
     * Get city
     *
     * @return Province
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param  Province $city
     * @return $this
     */
    public function setCity(Province $city = null)
    {
        $this->city = $city;

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
     * Set name
     *
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * Set about
     *
     * @param  string $about
     * @return $this
     */
    public function setAbout($about)
    {
        $this->about = $about;

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
     * Set country
     *
     * @param  Country $country
     * @return $this
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

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
     * Set addressLat
     *
     * @param  string $addressLat
     * @return $this
     */
    public function setAddressLat($addressLat)
    {
        $this->addressLat = $addressLat;

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
     * Set addressLong
     *
     * @param  string $addressLong
     * @return $this
     */
    public function setAddressLong($addressLong)
    {
        $this->addressLong = $addressLong;

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
     * @param  string $phone
     * @return $this
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
     * @param  string $fax
     * @return $this
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
     * @param  string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Set url
     *
     * @param  string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
     * Set wiki
     *
     * @param  string $wiki
     * @return $this
     */
    public function setWiki($wiki)
    {
        $this->wiki = $wiki;

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

    /**
     * Set logo
     *
     * @param  string $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param  mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return integer
     */
    public function getInstitutionType()
    {
        return $this->institution_type;
    }

    /**
     * @param  InstitutionTypes $institution_type
     * @return $this
     */
    public function setInstitutionType(InstitutionTypes $institution_type)
    {
        $this->institution_type = $institution_type;
        $this->setInstitutionTypeId($institution_type->getId());

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstitutionTypeId()
    {
        return $this->institution_type_id;
    }

    /**
     * @param  integer $institution_type_id
     * @return $this
     */
    public function setInstitutionTypeId($institution_type_id)
    {
        $this->institution_type_id = $institution_type_id;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
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
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
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
     * Set lft
     *
     * @param  integer $lft
     * @return $this
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

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
     * Set rgt
     *
     * @param  integer $rgt
     * @return $this
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return Institution
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set lvl
     *
     * @param  integer $lvl
     * @return $this
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Add authors
     *
     * @param  Author $authors
     * @return $this
     */
    public function addAuthor(Author $authors)
    {
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param Author $authors
     */
    public function removeAuthor(Author $authors)
    {
        $this->authors->removeElement($authors);
    }

    /**
     * Get authors
     *
     * @return Collection
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Add children
     *
     * @param  Institution $children
     * @return $this
     */
    public function addChild(Institution $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Institution $children
     */
    public function removeChild(Institution $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * @param  InstitutionTheme $institutionTheme
     * @return Institution
     */
    public function addInstitutionThemes(InstitutionTheme $institutionTheme)
    {
        $this->institutionThemes[] = $institutionTheme;

        return $this;
    }

    /**
     * @param InstitutionTheme $institutionTheme
     */
    public function removeInstitutionThemes(InstitutionTheme $institutionTheme)
    {
        $this->institutionThemes->removeElement($institutionTheme);
    }

    /**
     * @return Collection
     */
    public function getInstitutionThemes()
    {
        return $this->institutionThemes;
    }

    /**
     * Get themeId
     *
     * @return integer
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * Set themeId
     *
     * @param  integer $themeId
     * @return Institution
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;

        return $this;
    }

    /**
     * Get theme
     *
     * @return InstitutionTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme
     *
     * @param  InstitutionTheme   $theme
     * @return Institution
     */
    public function setTheme(InstitutionTheme $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return int
     */
    public function getDesignId()
    {
        return $this->designId;
    }

    /**
     * @param int $designId
     */
    public function setDesignId($designId)
    {
        $this->designId = $designId;
    }

    /**
     * @return InstitutionDesign
     */
    public function getDesign()
    {
        return $this->design;
    }

    /**
     * @param InstitutionDesign $design
     */
    public function setDesign(InstitutionDesign $design)
    {
        $this->design = $design;
    }

    /**
     * @return InstitutionDesign|ArrayCollection
     */
    public function getInstitutionDesigns()
    {
        return $this->institutionDesigns;
    }

    /**
     * @param InstitutionDesign|ArrayCollection $institutionDesigns
     */
    public function setInstitutionDesigns($institutionDesigns)
    {
        $this->institutionDesigns = $institutionDesigns;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Institution
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Institution
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \Ojs\JournalBundle\Entity\InstitutionTranslation $translation
     */
    public function removeTranslation(\Ojs\JournalBundle\Entity\InstitutionTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }
}
