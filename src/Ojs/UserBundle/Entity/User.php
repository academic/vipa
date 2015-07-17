<?php

namespace Ojs\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\Common\Helper\StringHelper;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\LocationBundle\Entity\Country;
use Ojs\LocationBundle\Entity\Province;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @ExclusionPolicy("all")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 * @GRID\Source(columns="id,username,email,status")
 */
class User implements Translatable, UserInterface, \Serializable, AdvancedUserInterface
{
    use GenericEntityTrait;

    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="user.id")
     */
    protected $id;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message="Username can't be blank")
     * @Assert\Length(min=3, minMessage="Username should be longer then 3 characters.")
     * @GRID\Column(title="user.username")
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
     * @GRID\Column(title="user.email")
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
    protected $isActive = true;

    /**
     * @var boolean
     * @Expose
     */
    protected $isAdmin = false;

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
     * @GRID\Column(title="user.status",filter="select",selectFrom="values",values={0="Passive",1="Active"} )
     */
    protected $status = 1;

    /** @var  ArrayCollection */
    protected $restrictedJournals;

    /**
     * @var array
     * @Expose
     */
    protected $roles;

    protected $avatar_options;

    /** @var  string */
    protected $gender;
    /** @var  string */
    protected $initials;
    /** @var  string */
    protected $url;
    /** @var  string */
    protected $phone;
    /** @var  string */
    protected $fax;
    /** @var  string */
    protected $address;
    /** @var  string */
    protected $billing_address;
    /** @var  string */
    protected $locales;
    /** @var  string */
    protected $disable_reason;
    /** @var  ArrayCollection */
    protected $authorDetails;

    protected $header_options;

    private $title;

    /**
     * @var Collection
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
     * @var Country
     * @Expose
     */
    private $country;

    /**
     * @var Province
     * @Expose
     */
    private $city;

    /**
     * @var string
     */
    private $header;
    /**
     * @var Collection
     */
    private $customFields;
    /**
     * @var Collection
     */
    private $oauthAccounts;

    /** @var  boolean */
    private $privacy;

    /** @var Collection */
    private $journalUsers;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->oauthAccounts = new ArrayCollection();
        $this->authorDetails = new ArrayCollection();
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
     * @param $key
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
     * @param  array $settings
     * @return User
     */
    public function setSettings($settings)
    {
        $this->settings = json_encode($settings);

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
     * @param  string $apiKey
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
    public function getToken()
    {
        return $this->token;
    }

    /*     * get
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */

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
        return "";
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
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->password,
            )
        );
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * Add role
     *
     * @param string $role
     * @return User
     */
    public function addRole($role)
    {
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if ($role === static::ROLE_ADMIN) {
            $this->setAdmin(true);

            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    /**
     * @param  boolean $isAdmin
     * @return boolean $this
     */
    public function setAdmin($isAdmin)
    {
        $this->isAdmin = !!$isAdmin;

        return $this;
    }

    /**
     *
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     *
     * @return Role[]
     */
    public function getRoles()
    {
        $this->roles[] = static::ROLE_DEFAULT;
        if ($this->isAdmin()) {
            $this->roles[] = static::ROLE_ADMIN;
        }

        return array_unique($this->roles);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param $role
     * @return $this
     */
    public function removeRole($role)
    {
        if ($role === static::ROLE_ADMIN) {
            $this->setAdmin(false);
        }
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     *
     * @return Collection
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
        return md5($this->getEmail()).md5(uniqid($this->getUsername(), true));
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
        $this->apiKey = StringHelper::generateKey();
    }

    /**
     * Add restrictedJournals
     *
     * @param  Journal $restrictedJournals
     * @return User
     */
    public function addRestrictedJournal(Journal $restrictedJournals)
    {
        $this->restrictedJournals[] = $restrictedJournals;

        return $this;
    }

    /**
     * Remove restrictedJournals
     *
     * @param Journal $restrictedJournals
     */
    public function removeRestrictedJournal(Journal $restrictedJournals)
    {
        $this->restrictedJournals->removeElement($restrictedJournals);
    }

    /**
     * Get restrictedJournals
     *
     * @return Collection
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
     * @return bool true if the user's account is non expired, false otherwise
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
     * @return bool true if the user is not locked, false otherwise
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
     * @return bool true if the user's credentials are non expired, false otherwise
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
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->getIsActive();
    }

    /**
     * Add customFields
     *
     * @param  CustomField $customFields
     * @return User
     */
    public function addCustomField(CustomField $customFields)
    {
        $this->customFields[] = $customFields;

        return $this;
    }

    /**
     * Remove customFields
     *
     * @param \Ojs\UserBundle\Entity\CustomField $customFields
     */
    public function removeCustomField(CustomField $customFields)
    {
        $this->customFields->removeElement($customFields);
    }

    /**
     * Get customFields
     *
     * @return Collection
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * Add oauthAccounts
     *
     * @param  UserOauthAccount $oauthAccounts
     * @return User
     */
    public function addOauthAccount(UserOauthAccount $oauthAccounts)
    {
        $this->oauthAccounts->add($oauthAccounts);

        return $this;
    }

    /**
     * Remove oauthAccounts
     *
     * @param UserOauthAccount $oauthAccounts
     */
    public function removeOauthAccount(UserOauthAccount $oauthAccounts)
    {
        $this->oauthAccounts->removeElement($oauthAccounts);
    }

    /**
     * @return mixed
     */
    public function getAvatarOptions()
    {
        return $this->avatar_options;
    }

    /**
     * @param mixed $avatar_options
     */
    public function setAvatarOptions($avatar_options)
    {
        $this->avatar_options = $avatar_options;
    }

    /**
     * @return mixed
     */
    public function getHeaderOptions()
    {
        return $this->header_options;
    }

    /**
     * @param mixed $header_options
     */
    public function setHeaderOptions($header_options)
    {
        $this->header_options = $header_options;
    }

    /**
     * Get oauthAccounts
     *
     * @return Collection
     */
    public function getOauthAccounts()
    {
        return $this->oauthAccounts;
    }

    /**
     * @return boolean
     */
    public function isPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param  boolean $privacy
     * @return $this
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param  string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthorDetails()
    {
        return $this->authorDetails;
    }

    /**
     * @param  Author $author
     * @return $this
     */
    public function addAuthorDetail(Author $author)
    {
        $this->authorDetails->add($author);

        return $this;
    }

    /**
     * @param  Author $author
     * @return $this
     */
    public function removeAuthorDetail(Author $author)
    {
        $this->authorDetails->remove($author);

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
    public function getDisableReason()
    {
        return $this->disable_reason;
    }

    /**
     * @param  string $disable_reason
     * @return $this
     */
    public function setDisableReason($disable_reason)
    {
        $this->disable_reason = $disable_reason;

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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param  string $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * @param  string $initials
     * @return $this
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param  mixed $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @param  string $locales
     * @return $this
     */
    public function setLocales($locales)
    {
        $this->locales = $locales;

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

    public function __toString()
    {
        return $this->getUsername().'( '.$this->getFullName().' ~ '.$this->getEmail().' ) ';
    }

    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastName();
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

    /**
     * @param  string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
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
        if ($this->getCountry() instanceof Country) {
            $data['country'] = $this->getCountry()->getName();
        }
        if ($this->getCity() instanceof Province) {
            $data['city'] = $this->getCity()->getName();
        }

        return json_encode($data);
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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return User
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Province
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param  Province $city
     * @return User
     */
    public function setCity(Province $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getJournalUsers()
    {
        return $this->journalUsers;
    }

    /**
     * @param Collection $journalUsers
     */
    public function setJournals($journalUsers)
    {
        $this->journalUsers = $journalUsers;
    }

    public function getJournalRoles(Journal $journal = null)
    {
        if(!$journal) {
            return [];
        }
        $journalRoles = [];
        $journalUsers = $this->getJournalUsers();

        /** @var JournalUser $journalUser */
        foreach ($journalUsers as $journalUser) {
            if ($journalUser->getJournal() == $journal) {
                foreach ($journalUser->getRoles() as $role) {
                    $journalRoles[] = [$journalUser->getJournal(), $role];
                }
            }
        }

        return $journalRoles;
    }
}
