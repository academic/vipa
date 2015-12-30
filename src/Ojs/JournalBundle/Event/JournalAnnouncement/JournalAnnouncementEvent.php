<?php

namespace Ojs\JournalBundle\Event\JournalAnnouncement;

use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalAnnouncementEvent extends Event
{
    /** @var JournalAnnouncement */
    private $journalAnnouncement;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalAnnouncement $journalAnnouncement
     */
    public function __construct(JournalAnnouncement $journalAnnouncement)
    {
        $this->journalAnnouncement = $journalAnnouncement;
    }

    /**
     * @return JournalAnnouncement
     */
    public function getJournalAnnouncement()
    {
        return $this->journalAnnouncement;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
