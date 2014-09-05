<?php

namespace Ojstr\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @ExclusionPolicy("all") 
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 */
class User extends \Ojstr\Common\Entity\GenericExtendedEntity implements UserInterface, \Serializable {

    /**
     * @var integer
     * @Expose
     */
    protected $id;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message="Username can't be blank")
     * @Assert\Length(min=3, minMessage="Username should be longer then 3 characters.")
     */
    protected $username;

    /**
     * @var string
     * @Expose
     */
    protected $password;

    /**
     * Temporary field
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message="Email can't be blank")
     * @Assert\Email
     */
    protected $email;

    /**
     * @var string
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     * @Expose
     */
    protected $lastName;

    /**
     * @var boolean
     * @Expose
     *
     */
    protected $isActive;

    /**
     * @var \DateTime
     */
    protected $lastlogin;

    /**
     *
     * @var string
     */
    protected $avatar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     */
    private $clientUsers;

    /**
     * @var \Doctrine\Common\Collections\Collection 
     */
    private $proxyUsers;

    /**
     * @var integer
     *
     */
    protected $status = 1;

    public function __construct() {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
        $this->subjects = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set avatar path
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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

    /*     * get
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * @return \DateTime
     */
    public function getLastogin() {
        return $this->lastlogin;
    }

    /**
     * @param \DateTime $lastlogin
     */
    public function setLastlogin(\DateTime $lastlogin) {
        $this->lastlogin = $lastlogin;
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        //$this->setPassword(NULL);
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                $this->username,
                $this->password
                ) = unserialize($serialized);
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles() {
        return $this->roles->toArray();
    }

    /**
     * Add roles
     *
     * @param \Ojstr\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Ojstr\UserBundle\Entity\Role $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Ojstr\UserBundle\Entity\Role $role
     */
    public function removeRole(\Ojstr\UserBundle\Entity\Role $role) {
        $this->roles->removeElement($role);
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects() {
        return $this->subjects;
    }

    /**
     * Add subject
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subjecgt
     * @return User
     */
    public function addSubject(\Ojstr\JournalBundle\Entity\Subject $subject) {
        $this->subjects[] = $subject;
        return $this;
    }

    /**
     * Remove subject
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subject
     */
    public function removeSubject(\Ojstr\JournalBundle\Entity\Subject $subject) {
        $this->subjects->removeElement($subject);
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientUsers() {
        return $this->clientUsers;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProxyUsers() {
        return $this->proxyUsers;
    }

    public function hasClient($userId) {
        $children = $this->clientUsers;
        foreach ($children as $child) {
            if ($child->getProxyUser()->getId() == $userId) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 
     * - fileName: The filename.
     * - fileExtension: The extension of the file (including the dot). Example: .jpg
     * - fileWithoutExt: The filename without the extension.
     * - filePath: The file path. Example: /my/path/filename.jpg
     * - fileMimeType: The mime-type of the file. Example: text/plain.
     * - fileSize: Size of the file in bytes. Example: 140000.
     * 
     * @param array $info
     */
    public function avatarFileCallback(array $info) {
        // noob
    }

}
