<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * InstitutionTypes
 */
class InstitutionTypes extends \Ojs\Common\Entity\GenericExtendedEntity
{
    public function __construct()
    {
        $this->institutions = new ArrayCollection();
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

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
     * Set name
     *
     * @param  string $name
     * @return InstitutionTypes
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
     * Set description
     *
     * @param  string $description
     * @return InstitutionTypes
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    private $slug;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    private $institutions;

    public function addInstitution(Institution $institution)
    {
        $this->institutions[] = $institution;
        return $this;
    }

    public function removeInstitution(Institution $institution)
    {
        $this->institutions->remove($institution);
        return $this;
    }

    public function getInstitutions()
    {
        return $this->institutions;
    }
}
