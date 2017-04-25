<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use BulutYazilim\LocationBundle\Entity\Country;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Institution
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,name,address,email")
 * @GRID\Source(columns="id,name,status", groups={"application"})
 */
class Institution extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\InstitutionTranslation")
     */
    protected $translations;

    /*
     * @var Institution
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $lft;
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
     * @var string
     * @Expose
     * @GRID\Column(field="city",title="city")
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
     * @var string
     */
    private $slug;
    /**
     * @var int
     */
    private $institutionType;

    /** @var ArrayCollection */
    private $institutionContacts;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->institutionContacts = new ArrayCollection();
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
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param  string $city
     * @return $this
     */
    public function setCity($city = null)
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
        return $this->getLogicalFieldTranslation('about', false);
    }

    /**
     * Set about
     *
     * @param  string $about
     * @return $this
     */
    public function setAbout($about)
    {
        $this->translate()->setAbout($about);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Vipa\JournalBundle\Entity\InstitutionTranslation
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
            $translation = new InstitutionTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setAbout($defaultTranslation->getAbout());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
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
        return $this->institutionType;
    }

    /**
     * @param  PublisherTypes $institutionType
     * @return $this
     */
    public function setInstitutionType(PublisherTypes $institutionType)
    {
        $this->institutionType = $institutionType;

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
     * @return ArrayCollection
     */
    public function getInstitutionContacts()
    {
        return $this->institutionContacts;
    }

    /**
     * @param ArrayCollection $institutionContacts
     */
    public function setInstitutionContacts($institutionContacts)
    {
        $this->institutionContacts = $institutionContacts;
    }

    public function __toString()
    {
        return $this->name;
    }
}
