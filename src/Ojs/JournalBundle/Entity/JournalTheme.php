<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\Common\Entity\GenericExtendedEntity;

/**
 * JournalTheme
 */
class JournalTheme extends GenericExtendedEntity
{

    /**
     * @var integer
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
     */
    private $journal;

    /**
     *
     * @var Theme
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
