<?php

namespace Okulbilisim\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 */
class Country
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $continent_code;

    /**
     * @var string
     */
    private $iso_code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set continent_code
     *
     * @param string $continentCode
     * @return Country
     */
    public function setContinentCode($continentCode)
    {
        $this->continent_code = $continentCode;

        return $this;
    }

    /**
     * Get continent_code
     *
     * @return string 
     */
    public function getContinentCode()
    {
        return $this->continent_code;
    }

    /**
     * Set iso_code
     *
     * @param string $isoCode
     * @return Country
     */
    public function setIsoCode($isoCode)
    {
        $this->iso_code = $isoCode;

        return $this;
    }

    /**
     * Get iso_code
     *
     * @return string 
     */
    public function getIsoCode()
    {
        return $this->iso_code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Add cities
     *
     * @param \Okulbilisim\LocationBundle\Entity\City $cities
     * @return Country
     */
    public function addCity(\Okulbilisim\LocationBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     *
     * @param \Okulbilisim\LocationBundle\Entity\City $cities
     */
    public function removeCity(\Okulbilisim\LocationBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCities()
    {
        return $this->cities;
    }
    /**
     * @var string
     */
    private $continent_name;


    /**
     * Set continent_name
     *
     * @param string $continentName
     * @return Country
     */
    public function setContinentName($continentName)
    {
        $this->continent_name = $continentName;

        return $this;
    }

    /**
     * Get continent_name
     *
     * @return string 
     */
    public function getContinentName()
    {
        return $this->continent_name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
