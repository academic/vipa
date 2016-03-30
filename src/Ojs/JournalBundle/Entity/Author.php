<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\UserBundle\Entity\User;
use BulutYazilim\LocationBundle\Entity\Country;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Author
 * @JMS\ExclusionPolicy("all")
 * @GRID\Source(columns="id,title,firstName,lastName,initials,email")
 */
class Author extends AbstractTranslatable
{
    use GenericEntityTrait;

    /** @var  Country */
    protected $country;

    /** @var  string */
    protected $city;

    /** @var  string */
    protected $url;

    /** @var  string */
    protected $phone;

    /** @var  string */
    protected $fax;

    /** @var  string */
    protected $billing_address;

    /**
     * @var integer
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\AuthorTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="firstname")
     */
    private $firstName;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="middlename")
     */
    private $middleName;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="lastname")
     */
    private $lastName;
    /**
     * @var string
     * @JMS\Expose
     * @GRID\Column(title="email")
     */
    private $email;
    /**
     * @var string
     * @JMS\Expose
     */
    private $firstNameTransliterated;
    /**
     * @var string
     * @JMS\Expose
     */
    private $middleNameTransliterated;
    /**
     * @var string
     * @JMS\Expose
     */
    private $lastNameTransliterated;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="initials")
     */
    private $initials;
    /**
     * @var string
     * @JMS\Expose
     */
    private $address;

    /**
     * @var Institution
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     */
    private $institution;
    /**
     * @var string
     * @JMS\Expose
     */
    private $biograpy;
    /**
     * @var string
     * @JMS\Expose
     */
    private $authorDetails;
    /**
     * @var integer
     * @JMS\Expose
     */
    private $userId;
    /**
     * @var User
     * @JMS\Expose
     */
    private $user;
    /**
     * title + firstname + middlename + lastname
     * @var string
     * @GRID\Column(title="fullname",field="fullname")
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     */
    private $fullName;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     */
    private $orcid;
    /**
     * @var boolean
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     */
    private $institutionNotListed;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","ArticleDetail"})
     */
    private $institutionName;
    /**
     * @var ArrayCollection|ArticleAuthor[]
     * @Jms\Expose
     */
    private $articleAuthors;
    /**
     * @var PersonTitle
     *
     * @GRID\Column(title="title")
     */
    private $title;

    public function __construct()
    {
        $this->articleAuthors = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getOrcid()
    {
        return $this->orcid;
    }

    /**
     * @param  string $orcid
     * @return $this
     */
    public function setOrcid($orcid)
    {
        $this->orcid = $orcid;

        return $this;
    }

    /**
     * @return ArrayCollection|ArticleAuthor[]
     */
    public function getArticleAuthors()
    {
        return $this->articleAuthors;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User $user
     * @return Author
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

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
     * @param  string $firstName
     * @return Author
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set middleName
     *
     * @param  string $middleName
     * @return Author
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

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
     * @param  string $lastName
     * @return Author
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

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
     * @return Author
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param  int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get firstNameTransliterated
     *
     * @return string
     */
    public function getFirstNameTransliterated()
    {
        return $this->firstNameTransliterated;
    }

    /**
     * Set firstNameTransliterated
     *
     * @param  string $firstNameTransliterated
     * @return Author
     */
    public function setFirstNameTransliterated($firstNameTransliterated)
    {
        $this->firstNameTransliterated = $firstNameTransliterated;

        return $this;
    }

    /**
     * Get middleNameTransliterated
     *
     * @return string
     */
    public function getMiddleNameTransliterated()
    {
        return $this->middleNameTransliterated;
    }

    /**
     * Set middleNameTransliterated
     *
     * @param  string $middleNameTransliterated
     * @return Author
     */
    public function setMiddleNameTransliterated($middleNameTransliterated)
    {
        $this->middleNameTransliterated = $middleNameTransliterated;

        return $this;
    }

    /**
     * Get lastNameTransliterated
     *
     * @return string
     */
    public function getLastNameTransliterated()
    {
        return $this->lastNameTransliterated;
    }

    /**
     * Set lastNameTransliterated
     *
     * @param  string $lastNameTransliterated
     * @return Author
     */
    public function setLastNameTransliterated($lastNameTransliterated)
    {
        $this->lastNameTransliterated = $lastNameTransliterated;

        return $this;
    }

    /**
     * Get initials
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * Set initials
     *
     * @param  string $initials
     * @return Author
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

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
     * @return Author
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get biograpy
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->getLogicalFieldTranslation('biograpy', false);
    }

    /**
     * Set biograpy
     *
     * @param  string $biograpy
     * @return Author
     */
    public function setBiography($biograpy)
    {
        $this->translate()->setBiography($biograpy);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\AuthorTranslation
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
            $translation = new AuthorTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setBiography($defaultTranslation->getBiography());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Add articleAuthor
     *
     * @param  ArticleAuthor $articleAuthor
     * @return Author
     */
    public function addArticleAuthor(ArticleAuthor $articleAuthor)
    {
        if (!$this->articleAuthors->contains($articleAuthor)) {
            $this->articleAuthors->add($articleAuthor);
            $articleAuthor->setAuthor($this);
        }

        return $this;
    }

    /**
     * Remove articleAuthor
     *
     * @param ArticleAuthor $articleAuthor
     * @return Author
     */
    public function removeArticleAuthor(ArticleAuthor $articleAuthor)
    {
        if ($this->articleAuthors->contains($articleAuthor)) {
            $this->articleAuthors->removeElement($articleAuthor);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  PersonTitle $title
     * @return $this
     */
    public function setTitle(PersonTitle $title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $this->fullName =
            (string)$this->title.' '.$this->firstName.' '.$this->middleName.' '.$this->lastName;

        // not sure if title should be added to fullname
        return $this->fullName;
    }

    /**
     * Get institution
     *
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set institution
     *
     * @param  Institution $institution
     * @return Author
     */
    public function setInstitution(Institution $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * @param  string $billing_address
     * @return $this
     */
    public function setBillingAddress($billing_address)
    {
        $this->billing_address = $billing_address;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param   $city
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
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param  string $fax
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param  string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorDetails()
    {
        return $this->authorDetails;
    }

    /**
     * @param string $authorDetails
     */
    public function setAuthorDetails($authorDetails)
    {
        $this->authorDetails = $authorDetails;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Author
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
     * @return Author
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionName()
    {
        return $this->institutionName;
    }

    /**
     * @param string $institutionName
     * @return $this
     */
    public function setInstitutionName($institutionName)
    {
        $this->institutionName = $institutionName;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isInstitutionNotListed()
    {
        return $this->institutionNotListed;
    }

    /**
     * @param boolean $institutionNotListed
     * @return $this
     */
    public function setInstitutionNotListed($institutionNotListed)
    {
        $this->institutionNotListed = $institutionNotListed;

        return $this;
    }

    public function __toString()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
}
