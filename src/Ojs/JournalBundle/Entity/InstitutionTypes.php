<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\Common\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\JournalBundle\Entity\InstitutionTypesTranslation;

/**
 * InstitutionTypes
 * @GRID\Source(columns="id,name,description")
 */
class InstitutionTypes extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

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

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\InstitutionTypesTranslation")
     */
    protected $translations;

    public function __construct()
    {
        $this->institutions = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\InstitutionTypesTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new InstitutionTypesTranslation();
            if(!is_null($defaultTranslation)){
                $translation->setName($defaultTranslation->getName());
                $translation->setDescription($defaultTranslation->getDescription());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;
        return $translation;
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
        $this->translate()->setName($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->translate()->getName();
    }

    /**
     * Set description
     *
     * @param  string           $description
     * @return InstitutionTypes
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->translate()->getDescription();
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
        if(!is_string($this->getName())){
            return $this->translations->first()->getName();
        }else{
            return $this->getName();
        }
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
}
