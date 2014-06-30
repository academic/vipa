<?php

namespace Ojstr\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Article
 * @ExclusionPolicy("all")
 */
class Article extends Model\ArticleModel {

    /**
     * auto-incremented article unique id
     * @var integer
     * @Expose
     */
    public $id;

    /**
     * @var integer
     * @Expose
     */
    public $status;

    /**
     * @var integer 
     */
    public $userId;

    /**
     * (optional)
     * @var string
     * @Expose
     */
    public $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     * @Expose
     */
    public $otherId;

    /**
     * @var integer
     * @Expose
     */
    public $journalId;

    /**
     * Original article title
     * @var string
     * @Expose
     */
    public $title;

    /**
     * Roman transliterated title
     * @var string
     * @Expose
     */
    public $titleTransliterated;

    /**
     * @var string
     * @Expose
     */
    public $subtitle;

    /**
     * @var string
     * @Expose
     */
    public $keywords;

    /**
     * Some artilce carries no authorship
     * @var boolean
     * @Expose
     */
    public $isAnonymous;

    /**
     * @var \DateTime
     * @Expose
     */
    public $pubdate;

    /**
     * @var string
     * @Expose
     */
    public $pubdateSeason;

    /**
     * @var string
     * @Expose
     */
    public $part;

    /**
     * @var integer
     * @Expose
     */
    public $firstPage;

    /**
     * @var integer
     * @Expose
     */
    public $lastPage;

    /**
     * @var string
     * @Expose
     */
    public $uri;

    /**
     * Original abstract
     * @var string
     * @Expose
     */
    public $abstract;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    public $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    public $citations;

    /**
     * @var \Ojstr\JournalBundle\Entity\Journal
     * @Expose
     */
    public $journal;

    /**
     * Constructor
     */
    public function __construct() {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->citations = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
