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
 * @GRID\Source(columns="id,period")
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
     * @GRID\Column(title="period")
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

    public function __toString()
    {
        if (!is_string($this->getPeriod())) {
            return $this->translations->first()->getName();
        } else {
            return $this->getPeriod();
        }
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->translate()->getPeriod();
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
