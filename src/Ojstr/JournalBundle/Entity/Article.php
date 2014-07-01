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
    private $id;

    /**
     * @var integer
     * @Expose
     */
    private $status;

    /**
     * user id of the owner of this article
     * @var integer
     * @Expose 
     */
    private $submitterId;

    /**
     * (optional)
     * @var string
     * @Expose
     */
    private $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     * @Expose
     */
    private $otherId;

    /**
     * @var integer
     * @Expose
     */
    private $journalId;

    /**
     * Original article title
     * @var string
     * @Expose
     */
    private $title;

    /**
     * Roman transliterated title
     * @var string
     * @Expose
     */
    private $titleTransliterated;

    /**
     * @var string
     * @Expose
     */
    private $subtitle;

    /**
     * @var string
     * @Expose
     */
    private $keywords;

    /**
     * Some artilce carries no authorship
     * @var boolean
     * @Expose
     */
    private $isAnonymous;

    /**
     * @var \DateTime
     * @Expose
     */
    private $pubdate;

    /**
     * @var string
     * @Expose
     */
    private $pubdateSeason;

    /**
     * @var string
     * @Expose
     */
    private $part;

    /**
     * @var integer
     * @Expose
     */
    private $firstPage;

    /**
     * @var integer
     * @Expose
     */
    private $lastPage;

    /**
     * @var string
     * @Expose
     */
    private $uri;

    /**
     * Original abstract
     * @var string
     * @Expose
     */
    private $abstract;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $citations;

    /**
     * @var \Ojstr\JournalBundle\Entity\Journal
     * @Expose
     */
    private $journal;

    /**
     * Constructor
     */
    public function __construct() {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->citations = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
