<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * InstitutionTypes
 * @GRID\Source(columns="id,name,description")
 */
class InstitutionTypes implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="name")
     */
    private $name;

    /**
     * @var string
     * @GRID\Column(title="description")
     */
    private $description;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var ArrayCollection|Institution[]
     */
    private $institutions;

    protected $translations;

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(InstitutionTypesTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function setTranslations($translations)
    {
        foreach($translations as $translation){
            $this->addTranslation($translation);
        }
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
     * Set name
     *
     * @param  string           $name
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
     * @param  string           $description
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

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param  mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param  Institution $institution
     * @return $this
     */
    public function addInstitution(Institution $institution)
    {
        $this->institutions[] = $institution;

        return $this;
    }

    /**
     * @param  Institution $institution
     * @return $this
     */
    public function removeInstitution(Institution $institution)
    {
        $this->institutions->remove($institution);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getInstitutions()
    {
        return $this->institutions;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return InstitutionTypes
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
     * @return InstitutionTypes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \Ojs\JournalBundle\Entity\InstitutionTypesTranslation $translation
     */
    public function removeTranslation(\Ojs\JournalBundle\Entity\InstitutionTypesTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }
}
