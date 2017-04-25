<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Citation
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,raw,type")
 */
class Citation implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     * @Expose
     */
    private $id;
    /**
     * @var string
     * @Expose
     */
    private $raw;
    /**
     * @var string
     * @Expose
     */
    private $type;
    /**
     * @var integer
     */
    private $orderNum;
    /**
     * @var Collection
     */
    private $articles;

    /**
     *
     */
    public function __construct()
    {
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @param  string   $type
     * @return Citation
     */
    public function setType($type)
    {
        $this->type = $type;

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
