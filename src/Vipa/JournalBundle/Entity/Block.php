<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Block
 * @GRID\Source(columns="id , translations.title, blockOrder")
 */
class Block extends AbstractTranslatable
{
    use GenericEntityTrait;

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
     */
    private $color;
    /**
     * @var integer
     */
    private $blockOrder;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\BlockTranslation")
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
     * @return mixed|null|\Vipa\JournalBundle\Entity\BlockTranslation
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
            $translation = new BlockTranslation();
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getLogicalFieldTranslation('title', false);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Block
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

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
     * Set content
     *
     * @param  string $content
     * @return Block
     */
    public function setContent($content)
    {
        $this->translate()->setContent($content);

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set color
     *
     * @param  string $color
     * @return Block
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get block_order
     *
     * @return integer
     */
    public function getBlockOrder()
    {
        return $this->blockOrder;
    }

    /**
     * Set blockOrder
     *
     * @param  integer $blockOrder
     * @return Block
     */
    public function setBlockOrder($blockOrder)
    {
        $this->blockOrder = $blockOrder;

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
     * Set journal
     *
     * @param  Journal $journal
     * @return Board
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
