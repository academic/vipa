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

}
