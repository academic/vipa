<?php

namespace Ojstr\JournalBundle\Entity;

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
     * @param \Ojstr\JournalBundle\Entity\Journal $journal
     */
    public function __construct($setting, $value, $journal)
    {
        $this->setting = $setting;
        $this->value = $value;
        $this->journal = $journal;
    }

}
