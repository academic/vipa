<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping\Source;
use Ojs\CoreBundle\Annotation\Display;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\CoreBundle\Entity\TranslateableTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;    

/**
 * JournalAnnouncement
 * @Source(columns="id, translations.title")
 */
class JournalAnnouncement extends AbstractTranslatable implements JournalItemInterface
{
    use DisplayTrait;
    use TranslateableTrait;

    /** @var Journal */
    private $journal;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     * @GRID\Column(field="translations.title", title="title")
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     * @Display\Image(filter="announcement_original")
     */
    private $image;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalAnnouncementTranslation")
     */
    protected $translations;

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
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalAnnouncementTranslation
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
            $translation = new JournalAnnouncementTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setContent($defaultTranslation->getContent());
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
     * @return JournalAnnouncement
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
        /** @var JournalPageTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return JournalAnnouncement
     */
    public function setContent($content)
    {
        $this->translate()->setContent($content);

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getLogicalFieldTranslation('content', false);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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

