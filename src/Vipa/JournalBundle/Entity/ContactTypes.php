<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Vipa\ApiBundle\Model\ContactTypesInterface;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * ContactTypes
 * @GRID\Source(columns="id,translations.name:translation_agg,translations.description:translation_agg", groupBy={"id"})
 */
class ContactTypes extends AbstractTranslatable implements ContactTypesInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\ContactTypesTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @GRID\Column(title="name", field="translations.name:translation_agg", safe=false, operatorsVisible=false)
     */
    private $name;
    /**
     * @var string
     * @GRID\Column(title="description", field="translations.description:translation_agg", safe=false, operatorsVisible=false)
     */
    private $description;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getLogicalFieldTranslation('description', false);
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Get description translations
     *
     * @return string
     */
    public function getDescriptionTranslations()
    {
        $titles = [];
        /** @var ContactTypesTranslation $translation */
        foreach($this->translations as $translation){
            if(!empty($translation->getDescription())){
                $titles[] = $translation->getDescription(). ' ['.$translation->getLocale().']';
            }
        }
        return implode('<br>', $titles);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getLogicalFieldTranslation('name', false);
    }

    /**
     * Get name translations
     *
     * @return string
     */
    public function getNameTranslations()
    {
        $titles = [];
        /** @var ContactTypesTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getName(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->translate()->setName($name);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Vipa\JournalBundle\Entity\ContactTypesTranslation
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
            $translation = new ContactTypesTranslation();
            if (!is_null($defaultTranslation)) {
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ContactTypes
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
     * @return ContactTypes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
