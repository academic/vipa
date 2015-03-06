<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Lang
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,code,name,rtl")
 */
class Lang extends GenericExtendedEntity
{

    /**
     * @var integer
     * @Expose()
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="lang.code")
     */
    private $code;

    /**
     * @var string
     * @Expose()
     * @GRID\Column(title="lang.name")
     */
    private $name;

    /**
     * @var boolean
     * @Expose()
     * @GRID\Column(title="lang.rtl")
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

    public function __toString() {
        return $this->name;
    }
}
