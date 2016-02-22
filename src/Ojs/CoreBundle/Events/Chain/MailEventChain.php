<?php

namespace Ojs\CoreBundle\Events\Chain;

use Ojs\CoreBundle\Events\EventDetail;
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

    /**
     * @param string $eventName
     * @return bool|EventDetail
     */
    public function getEventOptionsByName($eventName)
    {
        /** @var MailEventsInterface $eventsObject */
        foreach($this->getMailEvents() as $eventsObject){
            /** @var EventDetail $eventOption */
            foreach($eventsObject->getMailEventsOptions() as $eventOption){
                if($eventOption->getName() == $eventName){
                    return $eventOption;
                }
            }
        }
        return false;
    }

    /**
     * @param EventDetail $eventDetail
     * @return string
     */
    public function getEventParamsAsString(EventDetail $eventDetail)
    {
        $params = [];
        foreach($eventDetail->getTemplateParams() as $param){
            $params[] = '<code>[['.$param.']]</code>';
        }
        return implode(',', $params);
    }
}