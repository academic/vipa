<?php

namespace Ojs\AdminBundle\Events;

use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminEvent extends Event
{
    /**
     * @var Request $request
     */
    private $request;

    /**
     * @var Response $response
     */
    private $response;

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
     * @var
     */
    private $entity;

    /**
     * AdminEvent constructor.
     * @param Request $request
     * @param Journal|null $journal
     * @param Publisher|null $publisher
     * @param User|null $user
     * @param string $eventType
     * @param null $entity
     */
    public function __construct(Request $request,Journal $journal = null, Publisher $publisher = null, User $user = null, $eventType = '', $entity = null)
    {
        $this->request = $request;
        $this->journal = $journal;
        $this->user = $user;
        $this->eventType = $eventType;
        $this->entity = $entity;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
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
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     *
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $entity;
    }
}
