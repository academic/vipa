<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Citation
 */
class Citation extends \Ojstr\Common\Entity\GenericExtendedEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $raw;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $orderNum;

    /**
     * @var Array
     */
    protected $settings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     *
     */
    public function __construct()
    {
        $this->settings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param  \Ojstr\JournalBundle\Entity\Article $article
     * @return Subject
     */
    public function addArticle(\Ojstr\JournalBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Ojstr\JournalBundle\Entity\Article $articles
     */
    public function removeArticle(\Ojstr\JournalBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Remove setting
     *
     * @param \Ojstr\JournalBundle\Entity\CitationSetting $setting
     */
    public function removeSetting(\Ojstr\JournalBundle\Entity\CitationSetting $setting)
    {
        $this->settings->removeElement($setting);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($key)
    {
        return $this->settings[$key];
    }

    /**
     * Add setting
     *
     * @param  \Ojstr\JournalBundle\Entity\CitationSetting $setting
     * @return Citation
     */
    public function addSetting(\Ojstr\JournalBundle\Entity\CitationSetting $setting)
    {
        $this->settings[] = $setting;

        return $this;
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
     * Set raw
     *
     * @param  string   $raw
     * @return Citation
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get raw
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set type
     *
     * @param  string   $type
     * @return Citation
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Set orderNum
     *
     * @param  integer  $orderNum
     * @return Citation
     */
    public function setOrderNum($orderNum)
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    /**
     * Get orderNum
     *
     * @return integer
     */
    public function getOrderNum()
    {
        return $this->orderNum;
    }

}
