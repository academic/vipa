<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Period
 * @GRID\Source(columns="id,translations.period")
 * @JMS\ExclusionPolicy("all")
 */
class Period extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\PeriodTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @GRID\Column(title="period", field="translations.period", safe=false)
     */
    private $period;

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
     * @return string
     */
    public function __toString()
    {
        return $this->getPeriod();
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->getLogicalFieldTranslation('period', false);
    }

    /**
     * Get period translations
     *
     * @return string
     */
    public function getPeriodTranslations()
    {
        $titles = [];
        /** @var PeriodTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getPeriod(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * @param $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->translate()->setPeriod($period);
        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\PeriodTranslation
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
            $translation = new PeriodTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setPeriod($defaultTranslation->getPeriod());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }
}
