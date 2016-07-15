<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\CoreBundle\Params\PublisherStatuses;
use Ojs\UserBundle\Entity\User;
use BulutYazilim\LocationBundle\Entity\Country;
use BulutYazilim\LocationBundle\Entity\Province;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Publisher
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,translations.name,email,verified,status")
 * @GRID\Source(columns="id,translations.name,status", groups={"application"})
 */
class Publisher extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\PublisherTranslation")
     */
    protected $translations;

    /**
     * @var Publisher
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $lft;
    private $lvl;
    private $rgt;
    private $root;
    private $parent;
    /**
     * @var ArrayCollection|Publisher[]
     */
    private $children;
    /**
     * @var string
     * @Expose
     * @Grid\Column(title="Name", field="translations.name", safe=false)
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
     * @var Journal[]|ArrayCollection
     */
    private $journals;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var PublisherTypes
     */
    private $publisherType;

    /**
     * @var PublisherTheme
     */
    private $theme;
    /**
     * @var integer
    /**
     * @var PublisherTheme
     */
    private $design;
    /**
     * @var PublisherTheme[]|ArrayCollection
     * @Expose
     */
    private $publisherThemes;
    /**
     * @var PublisherDesign[]|ArrayCollection
     * @Expose
     */
    private $publisherDesigns;
    /**
     * @var boolean
     * @GRID\Column(title="verified")
     */
    private $verified = false;

    /**
     * @var int
     * @Grid\Column(field="status", title="status", filter="select", selectFrom="values", values={
     *     "-1"="application.status.rejected",
     *     "0"="application.status.onhold",
     *     "1"="application.status.complete"
     * })
     */
    private $status = PublisherStatuses::STATUS_ONHOLD;
    /**
     * @var ArrayCollection|User[]
     */
    private $publisherManagers;

    /**
     * List of Publisher Status
     * @var array
     */
    public static $statuses = array(
        PublisherStatuses::STATUS_REJECTED => 'application.status.rejected',
        PublisherStatuses::STATUS_ONHOLD => 'application.status.onhold',
        PublisherStatuses::STATUS_COMPLETE => 'application.status.complete',
    );

    public function __construct()
    {
        $this->journals = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->publisherThemes = new ArrayCollection();
        $this->publisherDesigns = new ArrayCollection();
        $this->publisherManagers = new ArrayCollection();
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
        if (!isset(Publisher::$statuses[$this->status])) {
            return null;
        }

        return Publisher::$statuses[$this->status];
    }

    /**
     *
     * @return Publisher
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Publisher|null $parent
     */
    public function setParent(Publisher $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return ArrayCollection|Publisher[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set root
     *
     * @param  integer $root
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Add journal
     * @param  Journal $journal
     * @return Publisher
     */
    public function addJournal(Journal $journal)
    {
        if(!$this->journals->contains($journal)) {
            $this->journals->add($journal);
            $journal->setPublisher($this);
        }

        return $this;
    }

    /**
     * Remove journal
     * @param Journal $journal
     * @return Publisher
     */
    public function removeJournal(Journal $journal)
    {
        if($this->journals->contains($journal)) {
            $this->journals->removeElement($journal);
            $journal->setPublisher(null);
        }
        return $this;
    }

    /**
     * Get journals
     * @return ArrayCollection|Journal[]
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
     * @return Publisher
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
        return $this->getLogicalFieldTranslation('name', false);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getNameTranslations()
    {
        $titles = [];
        /** @var PublisherTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getName(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Publisher
     */
    public function setName($name)
    {
        $this->translate()->setName($name);

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
     * @return Publisher
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
     * @return Publisher
     */
    public function setAbout($about)
    {
        $this->translate()->setAbout($about);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\PublisherTranslation
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
            $translation = new PublisherTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setAbout($defaultTranslation->getAbout());
                $translation->setName($defaultTranslation->getName());
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
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
     * @return Publisher
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return PublisherTypes
     */
    public function getPublisherType()
    {
        return $this->publisherType;
    }

    /**
     * @param  PublisherTypes $publisherType
     * @return Publisher
     */
    public function setPublisherType(PublisherTypes $publisherType)
    {
        $this->publisherType = $publisherType;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        if($this->verified == 1){
            return true;
        }
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
     * @return Publisher
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
     * @return Publisher
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return Publisher
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set lvl
     *
     * @param  integer $lvl
     * @return Publisher
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Add children
     *
     * @param  Publisher $child
     * @return Publisher
     */
    public function addChild(Publisher $child)
    {
        if(!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    /**
     * Remove children
     *
     * @param Publisher $child
     * @return Publisher
     */
    public function removeChild(Publisher $child)
    {
        if($this->children->contains($child)) {
            $this->children->removeElement($child);
            $child->setParent(null);
        }
        return $this;
    }

    /**
     * @param  PublisherTheme $publisherTheme
     * @return Publisher
     */
    public function addPublisherThemes(PublisherTheme $publisherTheme)
    {
        if(!$this->publisherThemes->contains($publisherTheme)) {
            $this->publisherThemes->add($publisherTheme);
        }

        return $this;
    }

    /**
     * @param PublisherTheme $publisherTheme
     * @return Publisher
     */
    public function removePublisherThemes(PublisherTheme $publisherTheme)
    {
        if($this->publisherThemes->contains($publisherTheme)) {
            $this->publisherThemes->removeElement($publisherTheme);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|PublisherTheme[]
     */
    public function getPublisherThemes()
    {
        return $this->publisherThemes;
    }

    /**
     * Get theme
     *
     * @return PublisherTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme
     *
     * @param  PublisherTheme $theme
     * @return Publisher
     */
    public function setTheme(PublisherTheme $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return PublisherDesign
     */
    public function getDesign()
    {
        return $this->design;
    }

    /**
     * @param PublisherDesign $design
     */
    public function setDesign(PublisherDesign $design)
    {
        $this->design = $design;
    }

    /**
     * @return PublisherDesign[]|ArrayCollection
     */
    public function getPublisherDesigns()
    {
        return $this->publisherDesigns;
    }

    /**
     * @param PublisherDesign[]|ArrayCollection $publisherDesigns
     */
    public function setPublisherDesigns($publisherDesigns)
    {
        $this->publisherDesigns = $publisherDesigns;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Publisher
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
     * @return Publisher
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add publisherManager
     *
     * @param  User $publisherManager
     * @return Publisher
     */
    public function addPublisherManager(User $publisherManager)
    {
        if(!$this->publisherManagers->contains($publisherManager)) {
            $this->publisherManagers->add($publisherManager);
        }
        return $this;
    }

    /**
     * Remove publisherManager
     *
     * @param User $publisherManager
     * @return Publisher
     */
    public function removePublisherManager(User $publisherManager)
    {
        if($this->publisherManagers->contains($publisherManager)) {
            $this->publisherManagers->removeElement($publisherManager);
        }
        return $this;
    }

    /**
     * Get publisherManagers
     *
     * @return ArrayCollection|User[]
     */
    public function getPublisherManagers()
    {
        return $this->publisherManagers;
    }

    /**
     * @return bool
     */
    public function isIndexable()
    {
        if($this->getStatus() == PublisherStatuses::STATUS_COMPLETE && $this->isVerified()){
            return true;
        }
        return false;
    }
}
