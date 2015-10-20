<?php

namespace Ojs\AdminBundle\EventListener;

use \Ojs\AdminBundle\Event\AdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminEventListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $mailSender;

    /** @var string */
    private $mailSenderName;

    /** @var RouterInterface */
    private $router;

    /**
     * @param RouterInterface $router
     * @param \Swift_Mailer   $mailer
     * @param string          $mailSender
     * @param string          $mailSenderName
     *
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        $mailSender,
        $mailSenderName
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
        );
    }

    /**
     *
     */
    public function onUserPassChange()
    {

    }

    /**
     *
     */
    public function onUserChange()
    {

    }

    /**
     *
     */
    public function onJournalContactChange()
    {

    }

    /**
     *
     */
    public function onJournalApplicationHappen()
    {

    }

    /**
     *
     */
    public function onJournalChange()
    {

    }

    /**
     *
     */
    public function onPublisherApplicationHappen()
    {

    }

    /**
     *
     */
    public function onPublisherManagerChange()
    {

    }

    /**
     *
     */
    public function onPublisherChange()
    {

    }

    /**
     *
     */
    public function onSubjectChange()
    {

    }

    /**
     *
     */
    public function onSettingsChange()
    {

    }
}