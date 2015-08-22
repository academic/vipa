<?php

namespace Ojs\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\Common\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post
 * @GRID\Source(columns="id ,title , status, post_type")
 */
abstract class Post extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    protected $id;

    private $title;

    private $content;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @Prezent\Translations(targetEntity="Ojs\CmsBundle\Entity\PostTranslation")
     */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\CmsBundle\Entity\PostTranslation
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
            $translation = new PostTranslation();

            if(!is_null($defaultTranslation)){
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
     * @return Post
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
        return $this->translate()->getTitle();
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
        return $this->translate()->getContent();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
