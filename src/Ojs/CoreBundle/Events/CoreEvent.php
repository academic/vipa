<?php

namespace Ojs\CoreBundle\Events;

use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CoreEvent extends Event
{
    /**
     * @var Journal $journal
     */
    private $journal;

    /**
     * @var User $user
     */
    private $user;

    /**
     * @var string $eventType
     */
    private $eventType;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * CoreEvent constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        foreach($options as $optionKey => $option){
            if(isset($this->$optionKey)){
                $this->$optionKey = $option;
            }
        }
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }
}
