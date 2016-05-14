<?php

namespace Ojs\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Translatable\Translatable;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use JMS\Serializer\Annotation as JMS;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\CoreBundle\Helper\StringHelper;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Entity\PersonTitle;
use Ojs\JournalBundle\Entity\Subject;
use BulutYazilim\LocationBundle\Entity\Country;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity(fields="username", message="That username is taken!")
 * @UniqueEntity(fields="email", message="That email is taken!")
 * @GRID\Source(columns="id,username,email,enabled")
 */
class User extends BaseUser implements Translatable, OAuthAwareUserProviderInterface
{
    use GenericEntityTrait;

    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     * @Assert\NotBlank(message="First name can't be blank")
     * @JMS\Expose
     */
    protected $firstName;

    /**
     * @var string
     * @Assert\NotBlank(message="Last name can't be blank")
     * @JMS\Expose
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var  ArrayCollection|Journal[]
     */
    protected $restrictedJournals;

    /**
     * @var  string
     */
    protected $gender;

    /**
     * @var  string
     */
    protected $initials;

    /**
     * @var  string
     */
    protected $url;

    /**
     * @JMS\Expose
     * @var string
     */
    protected $email;


    /**
     * @var  string
     */
    protected $phone;

    /**
     * @var  string
     */
    protected $fax;

    /**
     * @var  string
     */
    protected $address;

    /**
     * @var  string
     */
    protected $city;

    /**
     * @var  string
     */
    protected $billing_address;

    /**
     * @var  ArrayCollection|Author[]
     */
    protected $authorDetails;

    /**
     * @var PersonTitle
     */
    private $title;

    /**
     * @var Collection
     */
    private $subjects;

    /**
     * Json encoded settings string
     * @var String
     * @JMS\Expose
     */
    private $settings;

    /**
     * @var String
     * @JMS\Expose
     */
    private $about;

    /**
     * @var Country
     * @JMS\Expose
     */
    private $country;

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
    private $multipleMails;
    /**
     * @var Collection
     */
    private $oauthAccounts;

    /** @var boolean */
    private $privacy;

    /**
     * @JMS\Expose
     * @var Collection
     */
    private $journalUsers;

    /** @var Collection */
    private $journalRoles;

    public function __construct()
    {
        parent::__construct();
        $this->subjects = new ArrayCollection();
        $this->oauthAccounts = new ArrayCollection();
        $this->authorDetails = new ArrayCollection();
        $this->multipleMails = new ArrayCollection();
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


    public function generateToken()
    {
        return md5($this->getEmail()).md5(uniqid($this->getUsername(), true));
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
     * Add multipleMails
     *
     * @param  MultipleMail $multipleMails
     * @return User
     */
    public function addMultipleMail(MultipleMail $multipleMails)
    {
        $this->multipleMails[] = $multipleMails;

        return $this;
    }

    /**
     * Remove multipleMails
     *
     * @param \Ojs\UserBundle\Entity\MultipleMail $multipleMails
     */
    public function removeMultipleMail(MultipleMail $multipleMails)
    {
        $this->multipleMails->removeElement($multipleMails);
    }

    /**
     * Get multipleMails
     *
     * @return Collection
     */
    public function getMultipleMails()
    {
        return $this->multipleMails;
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
     * Get privacy
     *
     * @return boolean
     */
    public function getPrivacy()
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
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param  string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return ArrayCollection|Author[]
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
     * @return string
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
     * @return String
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param String $about
     * @return $this
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Type("string")
     * @JMS\SerializedName("text")
     * @JMS\Groups("search")
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername().'('.$this->getFullName().', '.$this->getEmail().')';
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

    /**
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
            'city' => $this->getCity()
        ];
        if ($this->getCountry() instanceof Country) {
            $data['country'] = $this->getCountry()->getName();
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
     * @return PersonTitle
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
     * @param Collection $journalUsers
     */
    public function setJournals($journalUsers)
    {
        $this->journalUsers = $journalUsers;
    }

    public function getJournalRoles(Journal $journal = null)
    {
        if (!$journal) {
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

    /**
     * @param Role[]|Collection $roles
     * @return $this
     */
    public function setJournalRoles($roles = array())
    {
        $this->journalRoles = $roles;

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add journalUser
     *
     * @param JournalUser $journalUser
     *
     * @return User
     */
    public function addJournalUser(JournalUser $journalUser)
    {
        $this->journalUsers[] = $journalUser;

        return $this;
    }

    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN') || $this->hasRole('ROLE_SUPER_ADMIN');
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        // TODO: Implement loadUserByOAuthUserResponse() method.
    }

    /**
     * @return bool
     */
    public function isIndexable()
    {
        if($this->isEnabled()){
            return true;
        }
        return false;
    }
}
