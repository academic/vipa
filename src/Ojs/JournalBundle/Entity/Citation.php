<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use GoDisco\AclTreeBundle\Annotation\AclParent;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Citation
 * @GRID\Source(columns="id,raw,type,articles")
 */
class Citation implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @GRID\Column(title="ID")
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
     * @var Collection
     * @GRID\Column(title="Articles", type="text",safe=false)
     * @AclParent
     */
    private $articles;

    /**
     *
     */
    public function __construct()
    {
        $this->settings = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * Add article
     *
     * @param  Article $article
     * @return Subject
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param Article $articles
     */
    public function removeArticle(Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Remove setting
     *
     * @param CitationSetting $setting
     */
    public function removeSetting(CitationSetting $setting)
    {
        $this->settings->removeElement($setting);
    }

    /**
     * Get settings
     *
     * @return Collection
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
     * @param  CitationSetting $setting
     * @return Citation
     */
    public function addSetting(CitationSetting $setting)
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
