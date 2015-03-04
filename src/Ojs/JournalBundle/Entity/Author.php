<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Author
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,title,firstName,lastName,initials,email")
 */
class Author extends \Ojs\Common\Entity\GenericExtendedEntity
{

    /**
     * @var integer
     * @Expose()
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="firstname")
     */
    private $firstName;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="middlename")
     */
    private $middleName;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="lastname")
     */
    private $lastName;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="email")
     */
    private $email;

    /**
     * @var string
     * @Expose()
     */
    private $firstNameTransliterated;

    /**
     * @var string
     * @Expose()
     */
    private $middleNameTransliterated;

    /**
     * @var string
     * @Expose()
     */
    private $lastNameTransliterated;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="initials")
     */
    private $initials;

    /**
     * @var string
     * @Expose()
     */
    private $address;

    /**
     * @var integer
     * @Expose()
     */
    private $institutionId;

    /**
     * @var integer
     * @Expose()
     */
    private $country;

    /**
     * @var string
     * @Expose()
     */
    private $summary;

    /**
     * @var integer
     * @Expose()
     */
    private $userId;

    /**
     * @var \Ojs\UserBundle\Entity\User
     * @Expose()
     */
    private $user;

    /**
     * title + firstname + middlename + lastname
     * @var string
     * @GRID\Column(title="fullname",field="fullname")
     */
    private $fullName;

    /**
     * @var string
     */
    private $orcid;

    /**
     * @return string
     */
    public function getOrcid()
    {
        return $this->orcid;
    }

    /**
     * @param string $orcid
     * @return $this
     */
    public function setOrcid($orcid)
    {
        $this->orcid = $orcid;
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $articleAuthors;

    public function __construct()
    {
        $this->articleAuthors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticleAuthors()
    {
        return $this->articleAuthors;
    }

    /**
     * Set user
     *
     * @param  \Ojs\UserBundle\Entity\User $user
     * @return Author
     */
    public function setUser(\Ojs\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojs\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
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
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
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
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return Author
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
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     *
     * @param  integer $userId
     * @return \Ojs\JournalBundle\Entity\Author
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
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
     * Get firstNameTransliterated
     *
     * @return string
     */
    public function getFirstNameTransliterated()
    {
        return $this->firstNameTransliterated;
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
     * Get middleNameTransliterated
     *
     * @return string
     */
    public function getMiddleNameTransliterated()
    {
        return $this->middleNameTransliterated;
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
     * Get lastNameTransliterated
     *
     * @return string
     */
    public function getLastNameTransliterated()
    {
        return $this->lastNameTransliterated;
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
     * Get initials
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
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
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set institutionId
     *
     * @param  integer $institutionId
     * @return Author
     */
    public function setInstitutionId($institutionId)
    {
        $this->institutionId = $institutionId;

        return $this;
    }

    /**
     * Get institutionId
     *
     * @return integer
     */
    public function getInstitutionId()
    {
        return $this->institutionId;
    }

    /**
     * Set country
     *
     * @param  integer $country
     * @return Author
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return integer
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set summary
     *
     * @param  string $summary
     * @return Author
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Add articleAuthors
     *
     * @param \Ojs\JournalBundle\Entity\ArticleAuthor $articleAuthors
     * @return Author
     */
    public function addArticleAuthor(\Ojs\JournalBundle\Entity\ArticleAuthor $articleAuthors)
    {
        $this->articleAuthors[] = $articleAuthors;

        return $this;
    }

    /**
     * Remove articleAuthors
     *
     * @param \Ojs\JournalBundle\Entity\ArticleAuthor $articleAuthors
     */
    public function removeArticleAuthor(\Ojs\JournalBundle\Entity\ArticleAuthor $articleAuthors)
    {
        $this->articleAuthors->removeElement($articleAuthors);
    }

    /**
     * @var string
     *
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $title;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->title . ' ' . $this->firstName . ' ' . $this->middleName . ' ' . $this->lastName;
    }


}
