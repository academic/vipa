<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ojs\Common\Entity\GenericExtendedEntity;
use Ojs\JournalBundle\Entity\Subject;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Expose;

/**
 * User
 * @ExclusionPolicy("all")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 */
class User extends GenericExtendedEntity implements UserInterface, \Serializable, AdvancedUserInterface
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
     * @var string
     */
    protected $apiKey;
    /**
     * @var integer
     */
    protected $status = 1;
    /** @var  ArrayCollection */
    protected $restrictedJournals;
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
     * @Expose
     */
    private $country_id;
    /**
     * @var Country
     * @Expose
     */
    private $country;
    /**
     * @var integer
     * @Expose
     */
    private $city_id;
    /**
     * @var City
     * @Expose
     */
    private $city;

    public function __construct()
    {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->oauthAccounts = new ArrayCollection();
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
     * @return mixed
     */
    public function getSetting($key)
    {
        $settings = $this->getSettings();
        return isset($settings[$key]) ? $settings[$key] : false;
    }

    /**
     * @return array|NULL
     */
    public function getSettings()
    {
        return json_decode($this->settings, 1);
    }

    /**
     *
     * @param array $settings
     * @return \Ojs\UserBundle\Entity\User
     */
    public function setSettings($settings)
    {
        $this->settings = json_encode($settings);
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
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
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
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /*     * get
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */

    /**
     * @param string $lastName
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
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Add role
     *
     * @param  \Ojs\UserBundle\Entity\Role $role
     * @return User
     */
    public function addRole(\Ojs\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @param \Ojs\UserBundle\Entity\Role $role
     */
    public function removeRole(\Ojs\UserBundle\Entity\Role $role)
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

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
    public function getUsername()
    {
        return $this->username;
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
     * Generates an API Key
     */
    public function generateApiKey()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apikey = '';
        for ($i = 0; $i < 64; $i++) {
            $apikey .= $characters[rand(0, strlen($characters) - 1)];
        }
        $apikey = base64_encode(sha1(uniqid('ue' . rand(rand(), rand())) . $apikey));
        $this->apiKey = $apikey;

    }

    /**
     * Add restrictedJournals
     *
     * @param \Ojs\JournalBundle\Entity\Journal $restrictedJournals
     * @return User
     */
    public function addRestrictedJournal(\Ojs\JournalBundle\Entity\Journal $restrictedJournals)
    {
        $this->restrictedJournals[] = $restrictedJournals;

        return $this;
    }

    /**
     * Remove restrictedJournals
     *
     * @param \Ojs\JournalBundle\Entity\Journal $restrictedJournals
     */
    public function removeRestrictedJournal(\Ojs\JournalBundle\Entity\Journal $restrictedJournals)
    {
        $this->restrictedJournals->removeElement($restrictedJournals);
    }

    /**
     * Get restrictedJournals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRestrictedJournals()
    {
        return $this->restrictedJournals;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool    true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return $this->getIsActive();
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool    true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return $this->getIsActive();
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool    true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return $this->getIsActive();
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool    true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->getIsActive();
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

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
        return $this;
    }

    /**
     * @var string
     */
    private $header;

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
    private $customFields;

    /**
     * Add customFields
     *
     * @param \Ojs\UserBundle\Entity\CustomField $customFields
     * @return User
     */
    public function addCustomField(\Ojs\UserBundle\Entity\CustomField $customFields)
    {
        $this->customFields[] = $customFields;

        return $this;
    }

    /**
     * Remove customFields
     *
     * @param \Ojs\UserBundle\Entity\CustomField $customFields
     */
    public function removeCustomField(\Ojs\UserBundle\Entity\CustomField $customFields)
    {
        $this->customFields->removeElement($customFields);
    }

    /**
     * Get customFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $oauthAccounts;

    /**
     * Add oauthAccounts
     *
     * @param \Ojs\UserBundle\Entity\UserOauthAccount $oauthAccounts
     * @return User
     */
    public function addOauthAccount(\Ojs\UserBundle\Entity\UserOauthAccount $oauthAccounts)
    {
        $this->oauthAccounts->add($oauthAccounts);

        return $this;
    }

    /**
     * Remove oauthAccounts
     *
     * @param \Ojs\UserBundle\Entity\UserOauthAccount $oauthAccounts
     */
    public function removeOauthAccount(\Ojs\UserBundle\Entity\UserOauthAccount $oauthAccounts)
    {
        $this->oauthAccounts->removeElement($oauthAccounts);
    }

    /**
     * Get oauthAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOauthAccounts()
    {
        return $this->oauthAccounts;
    }

    public function __toString()
    {
        return $this->getUsername() . '( ' . $this->getFullName() . ' ~ ' . $this->getEmail() . ' ) ';
    }

    public function toJson()
    {
        $data = [
            'username' => $this->getUsername(),
            'avatar' => $this->getAvatar(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'full_name' => $this->getFullName(),
            'header' => $this->getHeader(),
            'title' => $this->getTitle(),
        ];
        if($this->getCountry() instanceof Country){
            $data['country'] = $this->getCountry()->getName();
        }
        if($this->getCity() instanceof City){
            $data['city'] = $this->getCity()->getName();
        }
        return json_encode($data);
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     * @return User
     */
    public function setCity(City $city)
    {
        $this->city = $city;
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
     * @return User
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
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
     * @param Country $country
     * @return User
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param int $country_id
     * @return User
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
        return $this;
    }

    /** @var  boolean */
    private $privacy;

    /**
     * @return boolean
     */
    public function isPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param boolean $privacy
     * @return $this
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
        return $this;
    }

}
