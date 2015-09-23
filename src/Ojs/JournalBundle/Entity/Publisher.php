<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use OkulBilisim\LocationBundle\Entity\Country;
use OkulBilisim\LocationBundle\Entity\Province;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Publisher
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,name,address,email,verified")
 * @GRID\Source(columns="id,name,status", groups={"application"})
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
    private $publisher_type;
    /**
     * @var
     */
    private $publisher_type_id;

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
     * @var Collection
     * @Expose
     */
    private $publisherThemes;
    /**
     * @var PublisherDesign|ArrayCollection
     * @Expose
     */
    private $publisherDesigns;
    /**
     * @var boolean
     * @GRID\Column(title="verified")
     */
    private $verified = false;
    private $status = 0;
    /**
     * @var ArrayCollection|User[]
     */
    private $publisherManagers;

    /**
     * List of Publisher Status
     * @var array
     */
    public static $statuses = array(
        -1 => 'application.status.rejected',
        0 => 'application.status.onhold',
        1 => 'application.status.complete',
    );

    public function __construct()
    {
        $this->journals = new ArrayCollection();
        $this->authors = new ArrayCollection();
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
     * @return $this
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Publisher $parent = null)
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
        return $this->translate()->getAbout();
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
    public function getPublisherType()
    {
        return $this->publisher_type;
    }

    /**
     * @param  PublisherTypes $publisher_type
     * @return $this
     */
    public function setPublisherType(PublisherTypes $publisher_type)
    {
        $this->publisher_type = $publisher_type;
        $this->setPublisherTypeId($publisher_type->getId());

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublisherTypeId()
    {
        return $this->publisher_type_id;
    }

    /**
     * @param  integer $publisher_type_id
     * @return $this
     */
    public function setPublisherTypeId($publisher_type_id)
    {
        $this->publisher_type_id = $publisher_type_id;

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
     * @param  Publisher $children
     * @return $this
     */
    public function addChild(Publisher $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Publisher $children
     */
    public function removeChild(Publisher $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * @param  PublisherTheme $publisherTheme
     * @return Publisher
     */
    public function addPublisherThemes(PublisherTheme $publisherTheme)
    {
        $this->publisherThemes[] = $publisherTheme;

        return $this;
    }

    /**
     * @param PublisherTheme $publisherTheme
     */
    public function removePublisherThemes(PublisherTheme $publisherTheme)
    {
        $this->publisherThemes->removeElement($publisherTheme);
    }

    /**
     * @return Collection
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
     * @return PublisherDesign|ArrayCollection
     */
    public function getPublisherDesigns()
    {
        return $this->publisherDesigns;
    }

    /**
     * @param PublisherDesign|ArrayCollection $publisherDesigns
     */
    public function setPublisherDesigns($publisherDesigns)
    {
        $this->publisherDesigns = $publisherDesigns;
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
     * @return $this
     */
    public function addPublisherManager(User $publisherManager)
    {
        $this->publisherManagers[] = $publisherManager;
        return $this;
    }

    /**
     * Remove publisherManager
     *
     * @param User $publisherManager
     */
    public function removePublisherManager(User $publisherManager)
    {
        $this->publisherManagers->removeElement($publisherManager);
    }

    /**
     * Get publisherManagers
     *
     * @return Collection
     */
    public function getPublisherManagers()
    {
        return $this->publisherManagers;
    }
}
