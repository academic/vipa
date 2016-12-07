<?php

namespace Ojs\AdminBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\CoreBundle\Service\Mailer;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /** @var Mailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param Mailer $ojsMailer
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        Mailer $ojsMailer
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            AdminEvents::ADMIN_USER_CHANGE            => 'onUserChange',
            AdminEvents::ADMIN_USER_CHANGE_CREATE     => 'onUserChangeCreate',
            AdminEvents::ADMIN_CONTACT_CHANGE         => 'onJournalContactChange',
            AdminEvents::JOURNAL_APPLICATION_HAPPEN   => 'onJournalApplicationHappen',
            AdminEvents::ADMIN_JOURNAL_CHANGE         => 'onJournalChange',
            AdminEvents::PUBLISHER_APPLICATION_HAPPEN => 'onPublisherApplicationHappen',
            AdminEvents::PUBLISHER_CHANGE             => 'onPublisherChange',
            AdminEvents::ADMIN_SUBJECT_CHANGE         => 'onAdminSubjectChange',
            AdminEvents::SETTINGS_CHANGE              => 'onSettingsChange',
        ];
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChange(AdminEvent $event)
    {
        $entity = $event->getEntity();

        $params = [
            'user.username' => $entity->getUsername(),
            'user.fullName' => $entity->getFullName(),
            'eventType'     => $event->getEventType(),
        ];

        $this->sendAdminMail(AdminEvents::ADMIN_USER_CHANGE, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChangeCreate(AdminEvent $event)
    {
        $name = AdminEvents::ADMIN_USER_CHANGE_CREATE.'.created.user';
        $this->ojsMailer->sendEventMail($name, [$event->getEntity()], []);
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalContactChange(AdminEvent $event)
    {
        $params = [
            'contact'   => (string) $event->getEntity(),
            'eventType' => $event->getEventType(),
        ];

        $this->sendAdminMail(AdminEvents::ADMIN_CONTACT_CHANGE, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalApplicationHappen(AdminEvent $event)
    {
        // Notify admins first...
        /** @var Journal $journal */
        $journal = $event->getEntity();
        $this->sendAdminMail(AdminEvents::JOURNAL_APPLICATION_HAPPEN, [], ['journal.title' => $journal->getTitle()]);

        // ... and then the applicant user.
        if ($this->ojsMailer->currentUser() instanceof UserInterface){
            $user = $this->ojsMailer->currentUser();
        } else {
            $user = new User();
            $username = $this->ojsMailer->translator->trans('journal.manager');
            $user->setEmail($journal->getEmail())->setUsername($username);
        }

        $params = [
            'journal.title'   => $journal->getTitle(),
            'journal.phone'   => $journal->getPhone(),
            'journal.address' => $journal->getAddress(),
        ];

        $this->ojsMailer->sendEventMail(AdminEvents::JOURNAL_APPLICATION_HAPPEN.'.application.user', [$user], $params, null);
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalChange(AdminEvent $event)
    {
        $params = [
            'journal.title' => $event->getEntity()->getTitle(),
            'eventType'     => $event->getEventType(),
        ];

        $this->sendAdminMail(AdminEvents::ADMIN_JOURNAL_CHANGE, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherApplicationHappen(AdminEvent $event)
    {
        $params = ['publisher.name' => $event->getEntity()->getName()];
        $this->sendAdminMail(AdminEvents::PUBLISHER_APPLICATION_HAPPEN, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherChange(AdminEvent $event)
    {
        $params = [
            'publisher.name' => $event->getEntity()->getName(),
            'eventType'      => $event->getEventType(),
        ];

        $this->sendAdminMail(AdminEvents::PUBLISHER_CHANGE, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onAdminSubjectChange(AdminEvent $event)
    {
        $params = [
            'subject.subject' => $event->getEntity()->getSubject(),
            'eventType'       => $event->getEventType(),
        ];

        $this->sendAdminMail(AdminEvents::ADMIN_SUBJECT_CHANGE, [], $params);
    }

    /**
     * @param AdminEvent $event
     */
    public function onSettingsChange(AdminEvent $event)
    {
        $this->sendAdminMail(AdminEvents::SETTINGS_CHANGE);
    }

    /**
     * @param string $name
     * @param array $extraUsers
     * @param array $extraParams
     */
    private function sendAdminMail(
        string $name,
        array $extraUsers = [],
        array $extraParams = []
    ) {
        $users = array_merge($this->ojsMailer->getAdmins(), $extraUsers);
        $this->ojsMailer->sendEventMail($name, $users, $extraParams, null);
    }
}
