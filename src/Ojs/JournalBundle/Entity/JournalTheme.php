<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * JournalTheme
 * @GRID\Source(columns="id,journal.title,theme.title")
 */
class JournalTheme extends GenericExtendedEntity
{

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var integer
     */
    private $themeId;

    /**
     *
     * @var Journal
     * @GRID\Column(title="journal",field="journal.title")
     */
    private $journal;

    /**
     *
     * @var Theme
     * @GRID\Column(title="theme",field="theme.title")
     */
    private $theme;

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
     * Set journalId
     *
     * @param integer $journalId
     * @return JournalTheme
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer 
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     * @return JournalTheme
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;

        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer 
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    
    /**
     * Set journal
     * @param Journal $journal
     * @return JournalTheme
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal 
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set theme
     *
     * @param Theme $theme
     * @return JournalTheme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Get theme
     *
     * @return Theme 
     */
    public function getTheme()
    {
        return $this->theme;
    }
}
