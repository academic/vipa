<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 8.02.15
 * Time: 14:24
 */

namespace Ojs\JournalBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\UserBundle\Entity\User;

/**
 * This collection holds resumable institute suggestion data
 * @MongoDB\Document(collection="institute_suggestion")
 */
class InstituteSuggestion
{
    /**
     * @MongoDB\Id
     * @var integer
     */
    protected $id;

    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $type;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $name;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $slug;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $address;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $about;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $city;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $country;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $lat;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $lon;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $phone;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $fax;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $email;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $url;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $wiki_url;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $logo_image;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $header_image;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $tags;
    /**
     * @MongoDB\Date
     * @var string
     */
    protected $createdAt;
    /**
     * @MongoDB\Int
     * @var int
     */
    protected $user;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $merged;
    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getHeaderImage()
    {
        return $this->header_image;
    }

    /**
     * @param mixed $header_image
     */
    public function setHeaderImage($header_image)
    {
        $this->header_image = $header_image;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLogoImage()
    {
        return $this->logo_image;
    }

    /**
     * @param mixed $logo_image
     */
    public function setLogoImage($logo_image)
    {
        $this->logo_image = $logo_image;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getWikiUrl()
    {
        return $this->wiki_url;
    }

    /**
     * @param mixed $wiki_url
     */
    public function setWikiUrl($wiki_url)
    {
        $this->wiki_url = $wiki_url;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return boolean
     */
    public function isMerged()
    {
        return $this->merged;
    }

    /**
     * @param boolean $merged
     */
    public function setMerged($merged)
    {
        $this->merged = $merged;
    }

    
    
}