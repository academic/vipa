<?php

namespace Ojs\JournalBundle\Entity;

/**
 * CitationSetting
 */
class CitationSetting extends \Ojs\Common\Entity\GenericExtendedEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $citationId;

    /**
     * @var string
     */
    private $setting;

    /**
     * @var string
     */
    private $value;

    /**
     *
     * @var \Ojs\JournalBundle\Entity\Citation
     */
    protected $citation;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Citation        $citation
     * @return \Ojs\JournalBundle\Entity\CitationSetting
     */
    public function setCitation($citation)
    {
        $this->citation = $citation;

        return $this;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\Citation
     */
    public function getCitation()
    {
        return $this->citation;
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
     * Set citationId
     *
     * @param  integer         $citationId
     * @return CitationSetting
     */
    public function setCitationId($citationId)
    {
        $this->citationId = $citationId;

        return $this;
    }

    /**
     * Get citationId
     *
     * @return integer
     */
    public function getCitationId()
    {
        return $this->citationId;
    }

    /**
     * Set setting
     *
     * @param  string          $setting
     * @return CitationSetting
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;

        return $this;
    }

    /**
     * Get setting
     *
     * @return string
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Set value
     *
     * @param  string          $value
     * @return CitationSetting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}
