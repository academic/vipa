<?php

namespace Ojstr\JournalBundle\Entity\Model;

class AuthorModel extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     * @var string
     */
    private $firstNameTransliterated;

    /**
     * @var string
     */
    private $middleNameTransliterated;

    /**
     * @var string
     */
    private $lastNameTransliterated;

    /**
     * @var string
     */
    private $initials;

    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $institutionId;

    /**
     * @var integer
     */
    private $country;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \Ojstr\UserBundle/Entity/User
     */
    private $user;

    /**
     * Set user
     *
     * @param \Ojstr\UserBundle\Entity\User $user
     * @return Author
     */
    public function setUser(\Ojstr\UserBundle\Entity\User $user = null) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojstr\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @return integer
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * 
     * @param integer $userId
     * @return \Ojstr\JournalBundle\Entity\Author
     */
    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }
    /**
     * Set firstNameTransliterated
     *
     * @param string $firstNameTransliterated
     * @return Author
     */
    public function setFirstNameTransliterated($firstNameTransliterated) {
        $this->firstNameTransliterated = $firstNameTransliterated;
        return $this;
    }

    /**
     * Get firstNameTransliterated
     *
     * @return string 
     */
    public function getFirstNameTransliterated() {
        return $this->firstNameTransliterated;
    }

    /**
     * Set middleNameTransliterated
     *
     * @param string $middleNameTransliterated
     * @return Author
     */
    public function setMiddleNameTransliterated($middleNameTransliterated) {
        $this->middleNameTransliterated = $middleNameTransliterated;
        return $this;
    }

    /**
     * Get middleNameTransliterated
     *
     * @return string 
     */
    public function getMiddleNameTransliterated() {
        return $this->middleNameTransliterated;
    }

    /**
     * Set lastNameTransliterated
     *
     * @param string $lastNameTransliterated
     * @return Author
     */
    public function setLastNameTransliterated($lastNameTransliterated) {
        $this->lastNameTransliterated = $lastNameTransliterated;
        return $this;
    }

    /**
     * Get lastNameTransliterated
     *
     * @return string 
     */
    public function getLastNameTransliterated() {
        return $this->lastNameTransliterated;
    }

    /**
     * Set initials
     *
     * @param string $initials
     * @return Author
     */
    public function setInitials($initials) {
        $this->initials = $initials;
        return $this;
    }

    /**
     * Get initials
     *
     * @return string 
     */
    public function getInitials() {
        return $this->initials;
    }

    

    /**
     * Set address
     *
     * @param string $address
     * @return Author
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
     * Set institutionId
     *
     * @param integer $institutionId
     * @return Author
     */
    public function setInstitutionId($institutionId) {
        $this->institutionId = $institutionId;
        return $this;
    }

    /**
     * Get institutionId
     *
     * @return integer 
     */
    public function getInstitutionId() {
        return $this->institutionId;
    }

    /**
     * Set country
     *
     * @param integer $country
     * @return Author
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
     * Set summary
     *
     * @param string $summary
     * @return Author
     */
    public function setSummary($summary) {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary() {
        return $this->summary;
    }

}
