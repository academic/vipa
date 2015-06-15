<?php
namespace Okulbilisim\LocationBundle\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 * $this
 * @JMS\ExclusionPolicy("all")
 */
class Province
{
    /**
     * @var integer
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     * @JMS\Expose
     */
    private $name;

    /**
     * @var Country
     * @JMS\Expose
     */
    private $country;

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
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add Province
     *
     * @param  Country $country
     * @return $this
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
