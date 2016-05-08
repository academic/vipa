<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use JMS\Serializer\Annotation\Expose;

/**
 * Board
 * @GRID\Source(columns="id,translations.name,translations.description, boardOrder")
 */
class Board extends AbstractTranslatable implements JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     * @var string
     * @GRID\Column(title="name", field="translations.name", safe=false)
     */
    private $name;

    /**
     * @var int
     * @GRID\Column(title="order")
     */
    private $boardOrder = 0;

    /**
     * @var string
     * @GRID\Column(title="description", field="translations.description", safe=false)
     */
    private $description;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var Collection|BoardMember[]
     */
    private $boardMembers;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\BoardTranslation")
     * @Expose
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->boardMembers = new ArrayCollection();
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
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\BoardTranslation
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
            $translation = new BoardTranslation();
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
        /** @var BoardTranslation $translation */
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
     * Add boardMembers
     *
     * @param  BoardMember $boardMembers
     * @return Board
     */
    public function addBoardMember(BoardMember $boardMembers)
    {
        $this->boardMembers[] = $boardMembers;

        return $this;
    }

    /**
     * Remove boardMembers
     *
     * @param BoardMember $boardMembers
     */
    public function removeBoardMember(BoardMember $boardMembers)
    {
        $this->boardMembers->removeElement($boardMembers);
    }

    /**
     * Get boardMembers
     *
     * @return Collection
     */
    public function getBoardMembers()
    {
        return $this->boardMembers;
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
        /** @var BoardTranslation $translation */
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
     * @return int
     */
    public function getBoardOrder()
    {
        return $this->boardOrder;
    }

    /**
     * @param  int $boardOrder
     * @return $this
     */
    public function setBoardOrder($boardOrder)
    {
        $this->boardOrder = $boardOrder;

        return $this;
    }
}
