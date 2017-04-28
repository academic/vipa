<?php

namespace Vipa\AdminBundle\Events;

use Vipa\JournalBundle\Entity\Journal;
use Vipa\UserBundle\Entity\User;
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
     * @param array $options
     */
    public function __construct($options = [])
    {
        foreach($options as $optionKey => $option){
            if(property_exists($this, $optionKey)){
                $this->$optionKey = $option;
            }
        }
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
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
