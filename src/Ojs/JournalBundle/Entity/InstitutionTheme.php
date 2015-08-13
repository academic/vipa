<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * InstitutionTheme
 * @GRID\Source(columns="id,journal.title,title")
 */
class InstitutionTheme implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $css;

    /**
     * @var boolean
     */
    private $isPublic;

    /**
     *
     * @var Institution
     */
    private $institution;

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
     * Set Institution
     * @param  Institution      $institution
     * @return InstitutionTheme
     */
    public function setInstitution(Institution $institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get Institution
     *
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param string $css
     */
    public function setCss($css)
    {
        $this->css = $css;
    }

    /**
     * @return boolean
     */
    public function isIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }
}
