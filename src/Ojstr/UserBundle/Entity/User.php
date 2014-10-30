<?php

namespace Ojstr\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use \Ojstr\JournalBundle\Entity\Subject;
use \Ojstr\Common\Entity\GenericExtendedEntity;

/**
 * User
 * @ExclusionPolicy("all")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 */
class User extends GenericExtendedEntity implements UserInterface, \Serializable
{

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
     * @Assert\NotBlank(message="Password can't be blank")
     */
    protected $password = null;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message="Email can't be blank")
     * @Assert\Email
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotBlank(message="First name can't be blank")
     * @Expose
     */
    protected $firstName;

    /**
     * @var string
     * @Assert\NotBlank(message="Last name can't be blank")
     * @Expose
     */
    protected $lastName;

    /**
     * @var boolean
     * @Expose
     */
    protected $isActive;

    /**
     * @var string
     * @Expose
     */
    protected $token = null;

    /**
     * @var \DateTime
     */
    protected $lastlogin;

    /**
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
     * Json encoded settings string
     * @var String
     * @Expose
     */
    private $settings;

    /**
     * @var integer
     */
    protected $status = 1;

    public function __construct()
    {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
        $this->subjects = new ArrayCollection();
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
     * @return array|NULL
     */
    public function getSettings(){
        return json_decode($this->settings,1);
    } 
    /**
     * 
     * @param array $settings
     * @return \Ojstr\UserBundle\Entity\User
     */
    public function setSettings($settings){
        $this->settings = json_encode($settings);
        return $this;
    }
    /**
     * @param  string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param  string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param  integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param  string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /*     * get
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param String tr$lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param  boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param  String $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return \DateTime
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    /**
     * @param \DateTime $lastlogin
     */
    public function setLastlogin(\DateTime $lastlogin)
    {
        $this->lastlogin = $lastlogin;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        //$this->setPassword(NULL);
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
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
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Add role
     *
     * @param  \Ojstr\UserBundle\Entity\Role $role
     * @return User
     */
    public function addRole(\Ojstr\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @param \Ojstr\UserBundle\Entity\Role $role
     */
    public function removeRole(\Ojstr\UserBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param  Subject $subject
     * @return $this
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * @param Subject $subject
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);
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
    public function avatarFileCallback(array $info)
    {
        // noob
    }

    public function generateToken()
    {
        return md5($this->getEmail()) . md5(uniqid($this->getUsername(), true));
    }
}
