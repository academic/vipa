<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * CitationSetting
 * @GRID\Source(columns="id,setting,value")
 */
class CitationSetting implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @GRID\Column(title="citation.id")
     */
    private $id;

    /**
     * @var integer
     */
    private $citationId;

    /**
     * @var string
     * @GRID\Column(title="citation.setting")
     */
    private $setting;

    /**
     * @var string
     * @GRID\Column(title="value")
     */
    private $value;

    /**
     *
     * @var Citation
     */
    protected $citation;

    /**
     *
     * @param  Citation        $citation
     * @return CitationSetting
     */
    public function setCitation($citation)
    {
        $this->citation = $citation;

        return $this;
    }

    /**
     *
     * @return Citation
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

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CitationSetting
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
     * @return CitationSetting
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
