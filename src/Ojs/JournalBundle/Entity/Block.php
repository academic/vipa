<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Block
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
     */
    private $objectType;

    /**
     * @var integer
     */
    private $objectId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var Collection|BlockLink[]
     */
    private $links;
    /**
     * @var string
     */
    private $color;
    /**
     * @var integer
     */
    private $block_order;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\BlockTranslation")
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\BlockTranslation
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
     * Get objectType
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Set objectType
     *
     * @param  string $objectType
     * @return Block
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectId
     *
     * @param  integer $objectId
     * @return Block
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
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
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param  string $type
     * @return Block
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->translate()->getContent();
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
     * Add links
     *
     * @param  BlockLink $links
     * @return Block
     */
    public function addLink(BlockLink $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param BlockLink $links
     */
    public function removeLink(BlockLink $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
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
        return $this->block_order;
    }

    /**
     * Set block_order
     *
     * @param  integer $blockOrder
     * @return Block
     */
    public function setBlockOrder($blockOrder)
    {
        $this->block_order = $blockOrder;

        return $this;
    }

    public function __toString()
    {
        if (!is_string($this->getTitle())) {
            return $this->translations->first()->getTitle();
        } else {
            return $this->getTitle();
        }
    }
}
