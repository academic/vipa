<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\CoreBundle\Service\Mailer;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractJournalItemMailer implements EventSubscriberInterface
{
    /** @var Mailer */
    protected $ojsMailer;

    /** @var EntityManager */
    protected $em;

    /** @var UserInterface */
    protected $user;

    /** @var  RouterInterface */
    protected $router;

    /**
     * AbstractJournalItemMailer constructor.
     * @param Mailer $ojsMailer
     * @param RegistryInterface $registry
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     */
    public function __construct(
        Mailer $ojsMailer,
        RegistryInterface $registry,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    )
    {
        $this->ojsMailer = $ojsMailer;
        $this->em = $registry->getManager();
        $this->user = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser(): null;
        $this->router = $router;
    }

    protected function sendMail(JournalItemEvent $itemEvent, $item, $action)
    {
        $journalItem = $itemEvent->getItem();
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'A '.$item.' '.$action.' -> '.$journalItem->getJournal()->getTitle(),
                'A '.$item.' '.$action.' -> '.$journalItem->getJournal()->getTitle()
                .' -> by '.$this->user->getUsername()
            );
        }
    }
}
