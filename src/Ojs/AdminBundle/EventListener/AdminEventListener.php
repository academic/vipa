<?php

namespace Ojs\AdminBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\CoreBundle\Service\OjsMailer;
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

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager $em
     * @param OjsMailer $ojsMailer
     *
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        OjsMailer $ojsMailer
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
        return array(
            AdminEvents::ADMIN_USER_CHANGE => 'onUserChange',
            AdminEvents::ADMIN_USER_CHANGE_CREATE => 'onUserChangeCreate',
            AdminEvents::ADMIN_CONTACT_CHANGE => 'onJournalContactChange',
            AdminEvents::JOURNAL_APPLICATION_HAPPEN => 'onJournalApplicationHappen',
            AdminEvents::ADMIN_JOURNAL_CHANGE => 'onJournalChange',
            AdminEvents::PUBLISHER_APPLICATION_HAPPEN => 'onPublisherApplicationHappen',
            AdminEvents::PUBLISHER_CHANGE => 'onPublisherChange',
            AdminEvents::ADMIN_SUBJECT_CHANGE => 'onAdminSubjectChange',
            AdminEvents::SETTINGS_CHANGE => 'onSettingsChange',
        );
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::ADMIN_USER_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            /** @var User $entity */
            $entity = $event->getEntity();
            $transformParams = [
                'user.username'     => $entity->getUsername(),
                'user.fullName'     => $entity->getFullName(),
                'eventType'         => $event->getEventType(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChangeCreate(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::ADMIN_USER_CHANGE_CREATE.'.created.user');
        if(!$getMailEvent){
            return;
        }
        /** @var User $entity */
        $entity = $event->getEntity();
        $transformParams = [
            'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
            'receiver.username' => $entity->getUsername(),
            'receiver.fullName' => $entity->getFullName(),
        ];
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $entity,
            $getMailEvent->getSubject(),
            $template
        );
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalContactChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::ADMIN_CONTACT_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'contact'           => (string)$event->getEntity(),
                'eventType'         => $event->getEventType(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalApplicationHappen(AdminEvent $event)
    {
        /** @var Journal $journal */
        $journal = $event->getEntity();
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::JOURNAL_APPLICATION_HAPPEN);
        if(!$getMailEvent){
            goto lookforapplicationuser;
        }
        //send to admin user group
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'journal.title'     => $journal->getTitle(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
        lookforapplicationuser:

        //send to applier user
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::JOURNAL_APPLICATION_HAPPEN.'.application.user');
        if(!$getMailEvent){
            return;
        }
        $transformParams = [
            'journal.title'     => $journal->getTitle(),
            'journal.phone' => $journal->getPhone(),
            'journal.address' => $journal->getAddress(),
        ];
        if($this->ojsMailer->currentUser() instanceof UserInterface){
            $user = $this->ojsMailer->currentUser();
        }else{
            $user = new User();
            $user
                ->setEmail($journal->getEmail())
                ->setUsername($this->ojsMailer->translator->trans('journal.manager'))
                ;
        }
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $user,
            $getMailEvent->getSubject(),
            $template
        );
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::ADMIN_JOURNAL_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'journal.title'     => $event->getEntity()->getTitle(),
                'eventType'         => $event->getEventType(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherApplicationHappen(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::PUBLISHER_APPLICATION_HAPPEN);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'publisher.name'   => $event->getEntity()->getName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::PUBLISHER_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'publisher.name'   => $event->getEntity()->getName(),
                'eventType'         => $event->getEventType(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onAdminSubjectChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::ADMIN_SUBJECT_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'subject.subject'   => $event->getEntity()->getSubject(),
                'eventType'         => $event->getEventType(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onSettingsChange(AdminEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(AdminEvents::SETTINGS_CHANGE);
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getAdminUsers() as $user) {
            $transformParams = [
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }
}
