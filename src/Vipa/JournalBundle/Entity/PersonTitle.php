<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Doctrine\Common\Collections\ArrayCollection;
use Vipa\CoreBundle\Entity\GenericEntityTrait;

/**
 * PersonTitle
 * @GRID\Source(columns="id, translations.title:translation_agg", groupBy={"id"})
 */
class PersonTitle extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @Grid\Column(title="ID")
     */
    protected $id;

    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\PersonTitleTranslation")
     */
    protected $translations;

    /**
     * @var string
     * @Grid\Column(title="Title", field="translations.title:translation_agg", safe=false, operatorsVisible=false)
     */
    private $title;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Vipa\JournalBundle\Entity\PersonTitleTranslation
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
            $translation = new PersonTitleTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
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
     * Set title
     *
     * @param string $title
     *
     * @return PersonTitle
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getLogicalFieldTranslation('title', false);
    }

    /**
     * Get title translations
     *
     * @return string
     */
    public function getTitleTranslations()
    {
        $titles = [];
        /** @var PersonTitleTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getTitle();
    }
}

