<?php

namespace Ojs\JournalBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Event\JournalPage\JournalPageEvent;
use Ojs\JournalBundle\Event\JournalPage\JournalPageEvents;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JournalPageMailer implements EventSubscriberInterface
{
    /** @var OjsMailer */
    private $ojsMailer;

    /** @var EntityManager */
    private $em;

    /** @var UserInterface */
    private $user;

    /**
     * JournalPageMailer constructor.
     * @param OjsMailer $ojsMailer
     * @param RegistryInterface $registry
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(OjsMailer $ojsMailer, RegistryInterface $registry, TokenStorageInterface $tokenStorage)
    {
        $this->ojsMailer = $ojsMailer;
        $this->em = $registry->getManager();
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalPageEvents::POST_CREATE => 'onPostCreate',
            JournalPageEvents::POST_UPDATE => 'onPostUpdate',
            JournalPageEvents::PRE_DELETE => 'onPreDelete',
        );
    }

    /**
     * @param JournalPageEvent $journalPageEvent
     */
    public function onPostCreate(JournalPageEvent $journalPageEvent)
    {
        $this->sendMail($journalPageEvent, 'Created');
    }

    /**
     * @param JournalPageEvent $journalPageEvent
     */
    public function onPostUpdate(JournalPageEvent $journalPageEvent)
    {
        $this->sendMail($journalPageEvent, 'Updated');
    }

    /**
     * @param JournalPageEvent $journalPageEvent
     */
    public function onPreDelete(JournalPageEvent $journalPageEvent)
    {
        $this->sendMail($journalPageEvent, 'Deleted');
    }

    private function sendMail(JournalPageEvent $journalPageEvent, $action)
    {
        $mailUsers = $this->em->getRepository('OjsUserBundle:User')->findUsersByJournalRole(
            ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR']
        );

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'A Journal Page '.$action.' -> '.$journalPageEvent->getJournalPage()->getTitle(),
                'A Journal Page '.$action.' -> '.$journalPageEvent->getJournalPage()->getTitle()
                .' -> by '.$this->user->getUsername()
            );
        }
    }
}
