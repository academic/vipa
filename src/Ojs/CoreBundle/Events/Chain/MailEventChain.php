<?php

namespace Ojs\CoreBundle\Events\Chain;

use Ojs\CoreBundle\Events\MailEventsInterface;

class MailEventChain
{
    private $mailEvents;

    public function __construct()
    {
        $this->mailEvents = array();
    }

    public function addMailEvent(MailEventsInterface $mailEvent)
    {
        $this->mailEvents[] = $mailEvent;
    }

    public function getMailEvents()
    {
        return $this->mailEvents;
    }
}