<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Keyword
 */
class Keyword {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var integer
     */
    private $langId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return Keyword
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return Keyword
     */
    public function setLangId($langId) {
        $this->langId = $langId;
        return $this;
    }

    /**
     * Get langId
     *
     * @return integer 
     */
    public function getLangId() {
        return $this->langId;
    }

}
