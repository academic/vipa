<?php

namespace Ojs\JournalBundle\Entity;

use Prezent\Doctrine\Translatable\Annotation as Prezent;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * JournalPage
 * @GRID\Source(columns="id, translations.title")
 */
class JournalPage extends AbstractTranslatable implements JournalItemInterface
{
    /** @var Journal */
    private $journal;

    use GenericEntityTrait;
    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalPageTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @GRID\Column(title="title", field="translations.title", safe=false)
     */
    private $title;
    /**
     * @var string
     */
    private $body;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var boolean
     */
    private $visible;

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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * Get title translations
     *
     * @return string
     */
    public function getTitleTranslations()
    {
        $titles = [];
        /** @var JournalPageTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return JournalPage
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalPageTranslation
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
            $translation = new JournalPageTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setBody($defaultTranslation->getBody());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->translate()->getBody();
    }

    /**
     * Set body
     *
     * @param  string $body
     * @return JournalPage
     */
    public function setBody($body)
    {
        $this->translate()->setBody($body);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Is visible
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }
    /**
     * Set visible
     *
     * @param  boolean $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     *
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}

