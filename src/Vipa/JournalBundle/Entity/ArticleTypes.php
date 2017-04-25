<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * ArticleTypes
 * @GRID\Source(columns="id,translations.name:translation_agg,translations.description:translation_agg", groupBy={"id"})
 * @ExclusionPolicy("all")
 */
class ArticleTypes extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @Expose
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\ArticleTypesTranslation")
     * @Expose
     */
    protected $translations;
    /**
     * @var string
     * @GRID\Column(title="name", field="translations.name:translation_agg", safe=false, operatorsVisible=false)
     * @Expose
     */
    private $name;
    /**
     * @var string
     * @GRID\Column(title="description", field="translations.description:translation_agg", safe=false, operatorsVisible=false)
     * @Expose
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
     * Get description translations
     *
     * @return string
     */
    public function getDescriptionTranslations()
    {
        $titles = [];
        /** @var ArticleTypesTranslation $translation */
        foreach($this->translations as $translation){
            if(!empty($translation->getDescription())){
                $titles[] = $translation->getDescription(). ' ['.$translation->getLocale().']';
            }
        }
        return implode('<br>', $titles);
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
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Vipa\JournalBundle\Entity\ArticleTypesTranslation
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
            $translation = new ArticleTypesTranslation();
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
     * @return string
     */
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
        /** @var ArticleTypesTranslation $translation */
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
}
