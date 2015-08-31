<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use JMS\Serializer\Annotation as JMS;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalPeriod
 * @GRID\Source(columns="id,journal.title,period")
 */
class JournalPeriod extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     *
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     * @GRID\Column(title="period")
     */
    private $period;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalPeriodTranslation")
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalPeriodTranslation
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
            $translation = new AuthorTranslation();
            if(!is_null($defaultTranslation)){
                $translation->setPeriod($defaultTranslation->getPeriod());
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
     * Set journal
     * @param  Journal      $journal
     * @return JournalTheme
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
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
}
