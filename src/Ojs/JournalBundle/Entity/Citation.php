<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Citation
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,raw,type,articles")
 */
class Citation implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @GRID\Column(title="ID")
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $raw;

    /**
     * @var string
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
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
     * @return Citation
     */
    public function addArticle(Article $article)
    {
        if(!$this->articles->contains($article)){
            $this->articles->add($article);
            $article->addCitation($this);
        }

        return $this;
    }

    /**
     * Remove articles
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        if($this->articles->contains($article)){
            $this->articles->removeElement($article);
            $article->removeCitation($this);
        }
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

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Citation
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Citation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
