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

}
