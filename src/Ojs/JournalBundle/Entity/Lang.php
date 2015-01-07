<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericExtendedEntity;

/**
 * Lang
 * @ExclusionPolicy("all")
 */
class Lang extends GenericExtendedEntity
{

    /**
     * @var integer
     * @Expose()
     */
    private $id;

    /**
     * @var string
     * @Expose()
     */
    private $code;

    /**
     * @var string
     * @Expose()
     */
    private $name;

    /**
     * @var boolean
     * @Expose()
     */
    private $rtl;

    /**
     * @var boolean
     */
    private $translated;

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
     * Set code
     *
     * @param  string $code
     * @return Lang
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Lang
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rtl
     *
     * @param  boolean $rtl
     * @return Lang
     */
    public function setRtl($rtl)
    {
        $this->rtl = $rtl;

        return $this;
    }

    /**
     * Get rtl
     *
     * @return boolean
     */
    public function getRtl()
    {
        return $this->rtl;
    }

    /**
     * Set translated
     *
     * @param boolean $translated
     * @return Lang
     */
    public function setTranslated($translated)
    {
        $this->translated = $translated;

        return $this;
    }

    /**
     * Get translated
     *
     * @return boolean 
     */
    public function getTranslated()
    {
        return $this->translated;
    }

}
