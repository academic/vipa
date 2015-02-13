<?php

namespace Ojs\JournalBundle\Entity;

/**
 * Journal key-value settings
 */
class JournalSetting extends \Ojs\Common\Entity\GenericExtendedEntity
{
    private $journal;
    private $setting;
    private $value;

    /**
     *
     * @param string                              $setting
     * @param string                              $value
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     */
    public function __construct($setting, $value, $journal)
    {
        $this->setting = $setting;
        $this->value = $value;
        $this->journal = $journal;
    }


    /**
     * Set setting
     *
     * @param string $setting
     * @return JournalSetting
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
     * @param string $value
     * @return JournalSetting
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
     * Set journal
     *
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return JournalSetting
     */
    public function setJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojs\JournalBundle\Entity\Journal 
     */
    public function getJournal()
    {
        return $this->journal;
    }
}
