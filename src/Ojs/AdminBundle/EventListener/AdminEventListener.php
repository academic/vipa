<?php

namespace Ojs\AdminBundle\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;

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

    /** @var EntityManager */
    private $em;

    /**
     * @param RouterInterface $router
     * @param \Swift_Mailer   $mailer
     * @param EntityManager   $em
     * @param string          $mailSender
     * @param string          $mailSenderName
     *
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        EntityManager   $em,
        $mailSender,
        $mailSenderName
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->em = $em;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AdminEvents::ADMIN_USER_CHANGE => 'onUserChange', #+
            AdminEvents::ADMIN_CONTACT_CHANGE => 'onJournalContactChange', #+
            AdminEvents::JOURNAL_APPLICATION_HAPPEN => 'onJournalApplicationHappen', #+
            AdminEvents::ADMIN_JOURNAL_CHANGE => 'onJournalChange', #+
            AdminEvents::PUBLISHER_APPLICATION_HAPPEN => 'onPublisherApplicationHappen', #+
            AdminEvents::PUBLISHER_MANAGER_CHANGE => 'onPublisherManagerChange', #+
            AdminEvents::PUBLISHER_CHANGE => 'onPublisherChange', #+
            AdminEvents::SUBJECT_CHANGE => 'onSubjectChange',
            AdminEvents::SETTINGS_CHANGE => 'onSettingsChange',
        );
    }

    /**
     * @param AdminEvent $event
     */
    public function onUserChange(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Admin User Change -> '. $event->getEventType(),
                'Admin Event : Admin User Change -> '.$event->getEventType().' -> by '. $event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalContactChange(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Admin Contact Change -> '. $event->getEventType(),
                'Admin Event : Admin Contact Change -> '.$event->getEventType().' -> by '. $event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalApplicationHappen(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Journal Application Happen',
                'Admin Event : Journal Application Happen'
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onJournalChange(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Admin Journal Change -> '. $event->getEventType(),
                'Admin Event : Admin Journal Change -> '.$event->getEventType().' -> by '. $event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherApplicationHappen(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Publisher Application Happen',
                'Admin Event : Publisher Application Happen'
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherManagerChange(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Admin Publisher Manager Change -> '. $event->getEventType(),
                'Admin Event : Admin Publisher Manager Change -> '.$event->getEventType().' -> by '. $event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param AdminEvent $event
     */
    public function onPublisherChange(AdminEvent $event)
    {
        $adminUsers = $this->getAdminUsers();
        /** @var User $user */
        foreach($adminUsers as $user){
            $this->sendMail(
                $user,
                'Admin Event : Admin Publisher Change -> '. $event->getEventType(),
                'Admin Event : Admin Publisher Change -> '.$event->getEventType().' -> by '. $event->getUser()->getUsername()
            );
        }
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

    /**
     * @return \Doctrine\Common\Collections\Collection | User[]
     * @link http://stackoverflow.com/a/16692911
     */
    private function getAdminUsers()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param UserInterface $user
     * @param string $subject
     * @param string $body
     */
    private function sendMail(UserInterface $user, $subject, $body)
    {
        $message = $this->mailer->createMessage();
        $to = array($user->getEmail() => $user->getUsername());
        $message = $message
            ->setSubject($subject)
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }
}