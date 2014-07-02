<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lang
 */
class Lang {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $rtl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    public function __construct() {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \Ojstr\JournalBundle\Entity\Article $article
     * @return Subject
     */
    public function addArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Ojstr\JournalBundle\Entity\Article $article
     */
    public function removeArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles() {
        return $this->articles;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Lang
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Lang
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set rtl
     *
     * @param boolean $rtl
     * @return Lang
     */
    public function setRtl($rtl) {
        $this->rtl = $rtl;

        return $this;
    }

    /**
     * Get rtl
     *
     * @return boolean 
     */
    public function getRtl() {
        return $this->rtl;
    }

}
